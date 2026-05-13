<?php

namespace App\Services\PlanBuilder;

use App\Models\TreatmentPlan;

class PricingCalculator
{
    public function calculateLineTotal(int $quantity, float $unitPrice): float
    {
        return round($quantity * $unitPrice, 2);
    }

    public function calculateSubtotal(TreatmentPlan $plan): float
    {
        return round(
            (float) $plan->items()->where('is_optional', false)->sum('line_total'),
            2
        );
    }

    public function calculateDiscount(float $subtotal, ?string $discountType, ?float $discountValue): float
    {
        if (! $discountType || ! $discountValue) {
            return 0.0;
        }

        if ($discountType === 'percentage') {
            return round($subtotal * ($discountValue / 100), 2);
        }

        return round(min($discountValue, $subtotal), 2);
    }

    public function calculateTax(float $amount, float $taxRate): float
    {
        return round($amount * ($taxRate / 100), 2);
    }

    public function calculateDeposit(float $total, float $depositPercent): float
    {
        return round($total * ($depositPercent / 100), 2);
    }

    public function calculateTotal(
        TreatmentPlan $plan,
        ?string $discountType = null,
        ?float $discountValue = null,
        float $taxRate = 0,
        float $depositPercent = 0,
    ): array {
        $subtotal = $this->calculateSubtotal($plan);
        $discount = $this->calculateDiscount($subtotal, $discountType, $discountValue);
        $afterDiscount = round($subtotal - $discount, 2);
        $tax = $this->calculateTax($afterDiscount, $taxRate);
        $total = round($afterDiscount + $tax, 2);
        $deposit = $this->calculateDeposit($total, $depositPercent);

        return compact('subtotal', 'discount', 'tax', 'total', 'deposit');
    }
}
