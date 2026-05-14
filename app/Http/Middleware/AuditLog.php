<?php

namespace App\Http\Middleware;

use App\Models\AuditLog as AuditLogModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLog
{
    /**
     * Routes and methods that should be audited.
     */
    protected array $auditablePatterns = [
        'POST' => [
            'patient.plan.accept',
            'patient.plan.decline',
            'billing.subscribe',
        ],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->user()) {
            return $response;
        }

        if ($this->shouldAudit($request, $response)) {
            $this->recordAudit($request, $response);
        }

        return $response;
    }

    protected function shouldAudit(Request $request, Response $response): bool
    {
        // Audit all state-changing requests that succeeded
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return false;
        }

        // Only audit successful responses
        return $response->getStatusCode() >= 200 && $response->getStatusCode() < 400;
    }

    protected function recordAudit(Request $request, Response $response): void
    {
        $action = $request->method().' '.$request->path();

        // Determine a meaningful action label from the route name
        $routeName = $request->route()?->getName();
        if ($routeName) {
            $action = $routeName;
        }

        AuditLogModel::create([
            'clinic_id' => $request->user()->clinic_id,
            'user_id' => $request->user()->id,
            'action' => $action,
            'model_type' => null,
            'model_id' => null,
            'old_values' => null,
            'new_values' => $this->sanitiseInput($request->except([
                'password', 'password_confirmation', '_token', '_method',
            ])),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Remove sensitive fields from the recorded input.
     */
    protected function sanitiseInput(array $input): array
    {
        $sensitiveKeys = ['password', 'token', 'secret', 'card_number', 'cvv'];

        foreach ($sensitiveKeys as $key) {
            if (isset($input[$key])) {
                $input[$key] = '[redacted]';
            }
        }

        return $input;
    }
}
