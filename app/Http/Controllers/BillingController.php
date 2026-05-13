<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Webhooks\StripeWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Exceptions\IncompletePayment;

class BillingController extends Controller
{
    /**
     * Redirect to the Stripe billing portal.
     */
    public function portal(Request $request)
    {
        $clinic = Auth::user()?->clinic;

        if (! $clinic || ! $clinic->stripe_id) {
            return redirect()->route('billing.upgrade');
        }

        return $clinic->redirectToBillingPortal(route('dashboard'));
    }

    /**
     * Create a Stripe Checkout session for the given tier.
     */
    public function subscribe(Request $request, string $tier)
    {
        $priceMap = [
            'starter' => config('services.stripe.prices.starter'),
            'professional' => config('services.stripe.prices.professional'),
            'agency' => config('services.stripe.prices.agency'),
        ];

        if (! isset($priceMap[$tier]) || ! $priceMap[$tier]) {
            abort(400, 'Unknown plan tier.');
        }

        $clinic = Auth::user()?->clinic;

        if (! $clinic) {
            abort(403, 'No clinic associated with this account.');
        }

        try {
            return $clinic
                ->newSubscription('default', $priceMap[$tier])
                ->checkout([
                    'success_url' => route('dashboard'),
                    'cancel_url' => route('billing.upgrade'),
                    'customer_email' => $clinic->email ?? Auth::user()->email,
                    'metadata' => [
                        'clinic_id' => $clinic->id,
                        'tier' => $tier,
                    ],
                ]);
        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('dashboard')]
            );
        }
    }

    /**
     * Handle incoming Stripe webhooks.
     */
    public function webhook()
    {
        return app(StripeWebhookController::class)->__invoke(request());
    }
}
