<?php

namespace App\Livewire\PlanBuilder;

use App\Models\TreatmentPlan;
use App\Services\PlanBuilder\BuilderStateMachine;
use Illuminate\Support\Str;
use Livewire\Component;

class ReviewStep extends Component
{
    public TreatmentPlan $plan;

    public array $validationErrors = [];

    public string $sendMethod = 'email';

    public function mount(TreatmentPlan $plan): void
    {
        $this->plan = $plan->load(['patient', 'items', 'sections', 'videoConsultation']);
        $this->validate();
    }

    public function validate(array $rules = [], array $messages = [], array $attributes = []): array
    {
        $errors = [];

        if (! $this->plan->patient_id) {
            $errors[] = 'No patient assigned to this plan.';
        }

        if ($this->plan->items->isEmpty()) {
            $errors[] = 'At least one treatment item is required.';
        }

        if (! $this->plan->title) {
            $errors[] = 'Plan title is missing.';
        }

        $requiredItems = $this->plan->items->where('is_optional', false);

        foreach ($requiredItems as $item) {
            if (empty($item->name)) {
                $errors[] = 'One or more treatment items are missing a name.';
                break;
            }

            if ($item->unit_price <= 0) {
                $errors[] = 'One or more treatment items have no price set.';
                break;
            }
        }

        $this->validationErrors = $errors;

        return [];
    }

    public function send(): void
    {
        $this->validate();

        if (! empty($this->validationErrors)) {
            return;
        }

        $stateMachine = app(BuilderStateMachine::class);

        if ($stateMachine->canTransition($this->plan, 'sent')) {
            $this->plan = $stateMachine->transition($this->plan, 'sent', [
                'send_method' => $this->sendMethod,
            ]);

            $this->dispatch('plan-sent', method: $this->sendMethod);
        }
    }

    public function downloadPdf(): void
    {
        $this->dispatch('download-pdf', planId: $this->plan->id);
    }

    public function copyPublicLink(): void
    {
        if (! $this->plan->public_token) {
            $this->plan->update(['public_token' => Str::uuid()]);
            $this->plan->refresh();
        }

        $link = route('plan.public', $this->plan->public_token);

        $this->dispatch('copy-to-clipboard', text: $link);
    }

    public function render()
    {
        return view('livewire.plan-builder.review-step');
    }
}
