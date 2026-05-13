<?php

namespace App\Http\Middleware;

use App\Support\FeatureGates;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforcePlanFeatureGate
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (! $user || $user->isSuperAdmin()) {
            return $next($request);
        }

        $clinic = $user->clinic;

        if (! $clinic) {
            abort(403, 'No clinic associated with your account.');
        }

        if (! FeatureGates::clinicHasAccess($clinic, $feature)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'This feature requires a higher plan.',
                    'feature' => $feature,
                    'current_tier' => $clinic->plan_tier,
                ], 403);
            }

            return redirect()->route('billing.upgrade')
                ->with('upgrade_feature', $feature)
                ->with('upgrade_message', 'Upgrade your plan to access this feature.');
        }

        return $next($request);
    }
}
