<?php

namespace App\Livewire\PlanBuilder;

use App\Models\AnimationClip;
use App\Models\BeforeAfterCase;
use App\Models\TreatmentPlan;
use Illuminate\Support\Collection;
use Livewire\Component;

class VisualsStep extends Component
{
    public TreatmentPlan $plan;

    public array $animationClips = [];

    public array $beforeAfterCases = [];

    public Collection $availableClips;

    public Collection $availableCases;

    public function mount(TreatmentPlan $plan): void
    {
        $this->plan = $plan;

        $clipIds = $plan->items->flatMap(fn ($i) => $i->included_animation_clip_ids ?? [])->unique()->values()->toArray();
        $caseIds = $plan->items->flatMap(fn ($i) => $i->included_before_after_ids ?? [])->unique()->values()->toArray();

        $this->animationClips = $clipIds;
        $this->beforeAfterCases = $caseIds;

        $this->availableClips = AnimationClip::where('clinic_id', $plan->clinic_id)
            ->orWhereNull('clinic_id')
            ->get(['id', 'name', 'thumbnail_path', 'duration_seconds']);

        $this->availableCases = BeforeAfterCase::where('clinic_id', $plan->clinic_id)
            ->get(['id', 'title', 'before_image_path', 'after_image_path']);
    }

    public function addClip(int $id): void
    {
        if (! in_array($id, $this->animationClips, true)) {
            $this->animationClips[] = $id;
            $this->persistVisuals();
        }
    }

    public function removeClip(int $id): void
    {
        $this->animationClips = array_values(array_filter($this->animationClips, fn ($c) => $c !== $id));
        $this->persistVisuals();
    }

    public function addCase(int $id): void
    {
        if (! in_array($id, $this->beforeAfterCases, true)) {
            $this->beforeAfterCases[] = $id;
            $this->persistVisuals();
        }
    }

    public function removeCase(int $id): void
    {
        $this->beforeAfterCases = array_values(array_filter($this->beforeAfterCases, fn ($c) => $c !== $id));
        $this->persistVisuals();
    }

    private function persistVisuals(): void
    {
        $firstItem = $this->plan->items()->first();

        if ($firstItem) {
            $firstItem->update([
                'included_animation_clip_ids' => $this->animationClips,
                'included_before_after_ids' => $this->beforeAfterCases,
            ]);
        }

        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function render()
    {
        return view('livewire.plan-builder.visuals-step');
    }
}
