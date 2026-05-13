<?php

namespace App\Services\PlanBuilder;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanEvent;
use InvalidArgumentException;

class BuilderStateMachine
{
    private array $transitions = [
        'draft' => ['draft', 'sent'],
        'sent' => ['viewed', 'expired'],
        'viewed' => ['accepted', 'declined'],
        'accepted' => ['completed'],
    ];

    public function canTransition(TreatmentPlan $plan, string $toStatus): bool
    {
        return in_array($toStatus, $this->transitions[$plan->status] ?? [], true);
    }

    public function transition(TreatmentPlan $plan, string $toStatus, array $metadata = []): TreatmentPlan
    {
        if (! $this->canTransition($plan, $toStatus)) {
            throw new InvalidArgumentException(
                "Cannot transition plan from '{$plan->status}' to '{$toStatus}'."
            );
        }

        $timestamps = match ($toStatus) {
            'sent' => ['sent_at' => now()],
            'viewed' => ['viewed_at' => $plan->viewed_at ?? now(), 'last_viewed_at' => now()],
            'accepted' => ['accepted_at' => now()],
            'declined' => ['declined_at' => now()],
            'completed' => [],
            'expired' => [],
            default => [],
        };

        $plan->update(array_merge(['status' => $toStatus], $timestamps));

        TreatmentPlanEvent::create([
            'treatment_plan_id' => $plan->id,
            'event_type' => 'status_changed',
            'user_id' => auth()->id(),
            'patient_action' => false,
            'metadata' => array_merge($metadata, ['to_status' => $toStatus]),
            'created_at' => now(),
        ]);

        return $plan->fresh();
    }
}
