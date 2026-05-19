<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWhatsappBridgeSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('services.whatsapp_bridge.api_key');

        if (! $apiKey) {
            abort(500, 'WhatsApp bridge API key not configured');
        }

        $signature = $request->header('X-Bridge-Signature');

        if (! $signature) {
            abort(401, 'Missing signature');
        }

        $expectedSignature = hash_hmac('sha256', $request->getContent(), $apiKey);

        if (! hash_equals($expectedSignature, $signature)) {
            abort(401, 'Invalid signature');
        }

        return $next($request);
    }
}
