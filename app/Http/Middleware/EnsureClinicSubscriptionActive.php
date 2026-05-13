<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicSubscriptionActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isSuperAdmin()) {
            return $next($request);
        }

        $clinic = $user->clinic;

        if (! $clinic || ! $clinic->is_active || $clinic->isSuspended()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your clinic account is suspended.'], 403);
            }

            return redirect()->route('billing.suspended');
        }

        return $next($request);
    }
}
