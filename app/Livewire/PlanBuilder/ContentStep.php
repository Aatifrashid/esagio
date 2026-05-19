<?php

namespace App\Livewire\PlanBuilder;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanSection;
use Illuminate\Support\Collection;
use Livewire\Component;

class ContentStep extends Component
{
    public TreatmentPlan $plan;

    public string $introLetter = '';

    public Collection $sections;

    public string $guaranteeText = '';

    public string $legalDisclaimer = '';

    public function mount(TreatmentPlan $plan): void
    {
        $this->plan = $plan;

        $this->sections = $plan->sections()->orderBy('sort_order')->get();

        $intro = $plan->sections()->where('type', 'intro_letter')->first();
        $guarantee = $plan->sections()->where('type', 'guarantee')->first();
        $legal = $plan->sections()->where('type', 'legal_disclaimer')->first();

        $this->introLetter = $intro?->content ?? '';
        $this->guaranteeText = $guarantee?->content ?? '';
        $this->legalDisclaimer = $legal?->content ?? '';
    }

    public function addSection(): void
    {
        $nextOrder = ($this->sections->max('sort_order') ?? 0) + 1;

        TreatmentPlanSection::create([
            'treatment_plan_id' => $this->plan->id,
            'type' => 'custom',
            'title' => 'New Section',
            'content' => '',
            'sort_order' => $nextOrder,
            'is_visible' => true,
        ]);

        $this->sections = $this->plan->sections()->orderBy('sort_order')->get();
    }

    public function removeSection(int $id): void
    {
        TreatmentPlanSection::where('id', $id)
            ->where('treatment_plan_id', $this->plan->id)
            ->delete();

        $this->sections = $this->plan->sections()->orderBy('sort_order')->get();
        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function updateSection(int $id, array $data): void
    {
        $allowed = ['title', 'content', 'is_visible', 'sort_order'];

        TreatmentPlanSection::where('id', $id)
            ->where('treatment_plan_id', $this->plan->id)
            ->update(array_intersect_key($data, array_flip($allowed)));

        $this->sections = $this->plan->sections()->orderBy('sort_order')->get();
        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function toggleSectionVisibility(int $id): void
    {
        $section = TreatmentPlanSection::where('id', $id)
            ->where('treatment_plan_id', $this->plan->id)
            ->firstOrFail();

        $section->update(['is_visible' => ! $section->is_visible]);

        $this->sections = $this->plan->sections()->orderBy('sort_order')->get();
    }

    public function saveContent(): void
    {
        foreach (['intro_letter' => $this->introLetter, 'guarantee' => $this->guaranteeText, 'legal_disclaimer' => $this->legalDisclaimer] as $type => $content) {
            TreatmentPlanSection::updateOrCreate(
                ['treatment_plan_id' => $this->plan->id, 'type' => $type],
                ['title' => ucwords(str_replace('_', ' ', $type)), 'content' => $content, 'sort_order' => 99, 'is_visible' => true]
            );
        }

        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function render()
    {
        return view('livewire.plan-builder.content-step');
    }
}
