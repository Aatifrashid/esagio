<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimitApiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        $clinicId = $request->user()?->clinic_id ?? 'guest';
        $key = 'api:clinic:'.$clinicId;

        if (RateLimiter::tooManyAttempts($key, 60)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => $retryAfter,
            ], 429)->withHeaders([
                'Retry-After' => $retryAfter,
                'X-RateLimit-Limit' => 60,
                'X-RateLimit-Remaining' => 0,
            ]);
        }

        RateLimiter::hit($key, 60);

        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit', '60');
        $response->headers->set('X-RateLimit-Remaining', (string) RateLimiter::remaining($key, 60));

        return $response;
    }
}
