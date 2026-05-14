<?php

namespace App\Services\Integrations;

use App\Models\Clinic;
use App\Models\WebhookEndpoint;
use App\Models\WebhookLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookDispatcher
{
    public const EVENTS = [
        'plan.created',
        'plan.sent',
        'plan.accepted',
        'plan.declined',
        'patient.created',
        'appointment.scheduled',
    ];

    public function dispatch(Clinic $clinic, string $event, array $payload): int
    {
        $endpoints = WebhookEndpoint::withoutGlobalScopes()
            ->where('clinic_id', $clinic->id)
            ->where('is_active', true)
            ->get()
            ->filter(fn (WebhookEndpoint $ep) => $ep->listensTo($event));

        $sent = 0;

        foreach ($endpoints as $endpoint) {
            $this->send($endpoint, $event, $payload);
            $sent++;
        }

        return $sent;
    }

    private function send(WebhookEndpoint $endpoint, string $event, array $payload): void
    {
        $body = json_encode([
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'data' => $payload,
        ]);

        $signature = hash_hmac('sha256', $body, $endpoint->secret);

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Esagio-Signature' => $signature,
                    'X-Esagio-Event' => $event,
                ])
                ->withBody($body, 'application/json')
                ->post($endpoint->url);

            WebhookLog::create([
                'webhook_endpoint_id' => $endpoint->id,
                'event' => $event,
                'payload' => $payload,
                'response_code' => $response->status(),
                'response_body' => substr($response->body(), 0, 2000),
                'sent_at' => now(),
            ]);

            if ($response->successful()) {
                $endpoint->update([
                    'last_triggered_at' => now(),
                    'failure_count' => 0,
                ]);
            } else {
                $endpoint->increment('failure_count');
            }
        } catch (\Exception $e) {
            Log::warning("Webhook delivery failed for endpoint {$endpoint->id}: {$e->getMessage()}");

            WebhookLog::create([
                'webhook_endpoint_id' => $endpoint->id,
                'event' => $event,
                'payload' => $payload,
                'response_code' => null,
                'response_body' => $e->getMessage(),
                'sent_at' => now(),
            ]);

            $endpoint->increment('failure_count');

            if ($endpoint->failure_count >= 10) {
                $endpoint->update(['is_active' => false]);
            }
        }
    }
}
