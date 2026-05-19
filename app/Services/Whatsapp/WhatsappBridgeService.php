<?php

namespace App\Services\Whatsapp;

use App\Models\Clinic;
use App\Models\WhatsappSession;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WhatsappBridgeService
{
    private function client(): PendingRequest
    {
        return Http::baseUrl(config('services.whatsapp_bridge.base_url'))
            ->withHeaders([
                'X-API-Key' => config('services.whatsapp_bridge.api_key'),
            ])
            ->timeout(30)
            ->retry(2, 100);
    }

    public function createSession(Clinic $clinic): WhatsappSession
    {
        $sessionId = 'wa_' . Str::random(16);

        $response = $this->client()->post('/sessions', [
            'session_id' => $sessionId,
            'webhook_url' => route('whatsapp.webhook.message'),
            'status_webhook_url' => route('whatsapp.webhook.status'),
            'session_webhook_url' => route('whatsapp.webhook.session'),
        ]);

        $response->throw();

        return WhatsappSession::create([
            'clinic_id' => $clinic->id,
            'session_id' => $sessionId,
            'status' => 'connecting',
        ]);
    }

    public function getQrCode(WhatsappSession $session): ?string
    {
        $response = $this->client()->get("/sessions/{$session->session_id}/qr");

        if ($response->successful()) {
            $qr = $response->json('qr_code');
            $session->update(['qr_code' => $qr, 'status' => 'qr_pending']);

            return $qr;
        }

        return $session->qr_code;
    }

    public function sendMessage(WhatsappSession $session, string $to, string $body, ?string $mediaUrl = null): array
    {
        $payload = [
            'to' => $to,
            'body' => $body,
        ];

        if ($mediaUrl) {
            $payload['media_url'] = $mediaUrl;
        }

        $response = $this->client()->post("/sessions/{$session->session_id}/send", $payload);
        $response->throw();

        return $response->json();
    }

    public function disconnectSession(WhatsappSession $session): void
    {
        $this->client()->delete("/sessions/{$session->session_id}");

        $session->update(['status' => 'disconnected']);
    }

    public function getStatus(WhatsappSession $session): string
    {
        $response = $this->client()->get("/sessions/{$session->session_id}/status");

        if ($response->successful()) {
            return $response->json('status', 'unknown');
        }

        return 'unknown';
    }
}
