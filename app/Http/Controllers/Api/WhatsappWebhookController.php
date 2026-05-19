<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Patient;
use App\Models\WhatsappSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WhatsappWebhookController extends Controller
{
    public function messageReceived(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|string',
            'from' => 'required|string',
            'body' => 'nullable|string',
            'type' => 'nullable|string',
            'media_url' => 'nullable|string',
            'media_mime_type' => 'nullable|string',
            'message_id' => 'nullable|string',
            'push_name' => 'nullable|string',
        ]);

        $session = WhatsappSession::where('session_id', $request->session_id)->first();

        if (! $session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Normalise phone number (remove @s.whatsapp.net suffix if present)
        $phoneNumber = preg_replace('/@.*$/', '', $request->from);

        // Find or create conversation
        $conversation = Conversation::firstOrCreate(
            [
                'clinic_id' => $session->clinic_id,
                'channel' => 'whatsapp',
                'channel_identifier' => $phoneNumber,
            ],
            [
                'whatsapp_session_id' => $session->id,
                'status' => 'open',
            ]
        );

        // Auto-link to patient if not linked
        if (! $conversation->patient_id) {
            $patient = Patient::withoutGlobalScopes()
                ->where('clinic_id', $session->clinic_id)
                ->where(function ($q) use ($phoneNumber) {
                    $q->where('whatsapp_number', $phoneNumber)
                        ->orWhere('phone', $phoneNumber)
                        ->orWhere('whatsapp_number', '+' . $phoneNumber)
                        ->orWhere('phone', '+' . $phoneNumber);
                })
                ->first();

            if ($patient) {
                $conversation->update(['patient_id' => $patient->id]);
            }
        }

        // Deduplicate by external_id
        if ($request->message_id && Message::where('external_id', $request->message_id)->exists()) {
            return response()->json(['status' => 'duplicate']);
        }

        // Create message
        $message = $conversation->messages()->create([
            'direction' => 'inbound',
            'type' => $request->type ?? 'text',
            'body' => $request->body,
            'media_url' => $request->media_url,
            'media_mime_type' => $request->media_mime_type,
            'external_id' => $request->message_id,
            'status' => 'delivered',
            'metadata' => [
                'push_name' => $request->push_name,
            ],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return response()->json(['status' => 'ok', 'message_id' => $message->id]);
    }

    public function statusUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => 'required|string',
            'status' => 'required|string',
        ]);

        $message = Message::where('external_id', $request->message_id)->first();

        if ($message) {
            $message->update(['status' => $request->status]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function sessionStatus(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => 'required|string',
            'status' => 'required|string',
            'phone_number' => 'nullable|string',
            'qr_code' => 'nullable|string',
        ]);

        $session = WhatsappSession::where('session_id', $request->session_id)->first();

        if (! $session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $updateData = ['status' => $request->status];

        if ($request->phone_number) {
            $updateData['phone_number'] = $request->phone_number;
        }

        if ($request->qr_code) {
            $updateData['qr_code'] = $request->qr_code;
        }

        if ($request->status === 'connected') {
            $updateData['last_seen_at'] = now();
        }

        $session->update($updateData);

        return response()->json(['status' => 'ok']);
    }
}
