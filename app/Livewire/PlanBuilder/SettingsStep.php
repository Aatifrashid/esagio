<?php

namespace App\Livewire\PlanBuilder;

use App\Models\TreatmentPlan;
use Livewire\Component;

class SettingsStep extends Component
{
    public TreatmentPlan $plan;

    public string $currency = 'GBP';

    public string $language = 'en';

    public string $validUntil = '';

    public ?float $depositPercent = null;

    public ?float $depositFixed = null;

    public string $paymentTerms = '';

    public string $accessType = 'open';

    public string $planPassword = '';

    public string $internalNotes = '';

    public function mount(TreatmentPlan $plan): void
    {
        $this->plan = $plan;
        $this->currency = $plan->currency ?? 'GBP';
        $this->language = $plan->language ?? 'en';
        $this->validUntil = $plan->valid_until?->format('Y-m-d') ?? '';
        $this->depositFixed = $plan->deposit_amount ? (float) $plan->deposit_amount : null;
        $this->internalNotes = $plan->notes_internal ?? '';
        $this->planPassword = $plan->public_password ?? '';
        $this->accessType = $plan->public_password ? 'password' : ($plan->valid_until ? 'expiring' : 'open');
    }

    public function saveSettings(): void
    {
        $this->validate([
            'currency' => 'required|string|size:3',
            'language' => 'required|string|max:10',
            'validUntil' => 'nullable|date',
            'depositPercent' => 'nullable|numeric|min:0|max:100',
            'depositFixed' => 'nullable|numeric|min:0',
            'accessType' => 'required|in:open,password,expiring',
            'planPassword' => 'nullable|string|max:100',
            'internalNotes' => 'nullable|string',
        ]);

        $depositAmount = null;

        if ($this->depositPercent) {
            $depositAmount = ($this->plan->total_amount * $this->depositPercent) / 100;
        } elseif ($this->depositFixed) {
            $depositAmount = $this->depositFixed;
        }

        $this->plan->update([
            'currency' => $this->currency,
            'language' => $this->language,
            'valid_until' => $this->validUntil ?: null,
            'deposit_amount' => $depositAmount,
            'notes_internal' => $this->internalNotes,
            'public_password' => $this->accessType === 'password' ? $this->planPassword : null,
        ]);

        $this->dispatch('plan-updated')->to(Builder::class);
        $this->dispatch('settings-saved');
    }

    public function render()
    {
        return view('livewire.plan-builder.settings-step');
    }
}
