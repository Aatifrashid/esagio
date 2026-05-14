<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboardingComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->clinic) {
            return $next($request);
        }

        $settings = $user->clinic->settings ?? [];
        $completed = $settings['onboarding_completed_at'] ?? null;

        if (! $completed && ! $request->routeIs('onboarding*') && ! $request->routeIs('logout')) {
            return redirect()->route('onboarding');
        }

        return $next($request);
    }
}
