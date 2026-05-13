<?php

namespace App\Http\Controllers\Webhooks;

use App\Models\Clinic;
use Laravel\Cashier\Http\Controllers\WebhookController;

class StripeWebhookController extends WebhookController
{
    /**
     * Handle a new subscription being created.
     */
    public function handleCustomerSubscriptionCreated(array $payload): void
    {
        $stripeId = $payload['data']['object']['customer'] ?? null;
        $tier = $payload['data']['object']['metadata']['tier'] ?? null;

        if ($stripeId && $tier) {
            Clinic::where('stripe_id', $stripeId)->update(['plan_tier' => $tier, 'is_active' => true]);
        }
    }

    /**
     * Handle a subscription being updated (upgrade or downgrade).
     */
    public function handleCustomerSubscriptionUpdated(array $payload): void
    {
        $stripeId = $payload['data']['object']['customer'] ?? null;
        $tier = $payload['data']['object']['metadata']['tier'] ?? null;

        if ($stripeId && $tier) {
            Clinic::where('stripe_id', $stripeId)->update(['plan_tier' => $tier]);
        }
    }

    /**
     * Handle a subscription being cancelled or expired.
     */
    public function handleCustomerSubscriptionDeleted(array $payload): void
    {
        $stripeId = $payload['data']['object']['customer'] ?? null;

        if ($stripeId) {
            Clinic::where('stripe_id', $stripeId)->update([
                'plan_tier' => 'free',
                'stripe_subscription_id' => null,
            ]);
        }
    }

    /**
     * Suspend clinic after three consecutive payment failures.
     */
    public function handleInvoicePaymentFailed(array $payload): void
    {
        $stripeId = $payload['data']['object']['customer'] ?? null;
        $attemptCount = $payload['data']['object']['attempt_count'] ?? 0;

        if ($stripeId && $attemptCount >= 3) {
            Clinic::where('stripe_id', $stripeId)->update([
                'is_active' => false,
                'suspended_at' => now(),
                'suspended_reason' => 'Payment failed after '.$attemptCount.' attempts.',
            ]);
        }
    }
}
