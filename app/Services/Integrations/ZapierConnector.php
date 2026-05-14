<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZapierConnector
{
    public function triggerZap(string $webhookUrl, string $event, array $data): bool
    {
        try {
            $response = Http::timeout(10)->post($webhookUrl, [
                'event' => $event,
                'timestamp' => now()->toIso8601String(),
                'data' => $data,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::warning("Zapier trigger failed: {$e->getMessage()}");

            return false;
        }
    }
}
