<?php

namespace App\Livewire\PlanBuilder;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\TreatmentTemplate;
use App\Services\PlanBuilder\ItemSuggestionEngine;
use Illuminate\Support\Collection;
use Livewire\Component;

class TreatmentStep extends Component
{
    public TreatmentPlan $plan;

    public Collection $items;

    public string $templateSearch = '';

    public array $templates = [];

    public array $recentTemplates = [];

    public array $suggestions = [];

    public string $activeTab = 'quick_add';

    public bool $showPhases = false;

    public Collection $phases;

    public function mount(TreatmentPlan $plan): void
    {
        $this->plan = $plan;
        $this->items = $plan->items()->orderBy('position')->get();
        $this->phases = $plan->phases;
        $this->recentTemplates = TreatmentTemplate::where('clinic_id', $plan->clinic_id)
            ->orderByDesc('usage_count')
            ->limit(10)
            ->get(['id', 'name', 'description_short', 'code'])
            ->toArray();

        $this->refreshSuggestions();
    }

    public function updatedTemplateSearch(): void
    {
        $this->searchTemplates();
    }

    public function searchTemplates(): void
    {
        if (strlen($this->templateSearch) < 2) {
            $this->templates = [];

            return;
        }

        $term = '%'.$this->templateSearch.'%';

        $this->templates = TreatmentTemplate::where('clinic_id', $this->plan->clinic_id)
            ->where('is_active', true)
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('code', 'like', $term)
                    ->orWhere('description_short', 'like', $term);
            })
            ->limit(15)
            ->get(['id', 'name', 'description_short', 'code'])
            ->toArray();
    }

    public function addFromTemplate(int $templateId): void
    {
        $template = TreatmentTemplate::findOrFail($templateId);

        $nextPosition = $this->items->max('position') + 1;

        $item = TreatmentPlanItem::create([
            'treatment_plan_id' => $this->plan->id,
            'treatment_template_id' => $template->id,
            'name' => $template->name,
            'description' => $template->description_short,
            'quantity' => 1,
            'unit_price' => 0,
            'line_total' => 0,
            'position' => $nextPosition,
            'included_animation_clip_ids' => $template->default_animation_clip_ids ?? [],
            'included_before_after_ids' => $template->default_before_after_ids ?? [],
            'is_optional' => false,
        ]);

        $template->increment('usage_count');

        $this->items = $this->plan->items()->orderBy('position')->get();
        $this->plan->recalculateTotal();
        $this->plan->refresh();

        $this->templateSearch = '';
        $this->templates = [];

        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function addCustomItem(): void
    {
        $nextPosition = $this->items->max('position') + 1;

        TreatmentPlanItem::create([
            'treatment_plan_id' => $this->plan->id,
            'name' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'line_total' => 0,
            'position' => $nextPosition,
            'is_optional' => false,
        ]);

        $this->items = $this->plan->items()->orderBy('position')->get();
    }

    public function updateItem(int $itemId, string $field, mixed $value): void
    {
        $allowed = ['name', 'description', 'quantity', 'unit_price', 'notes', 'procedure_phase', 'is_optional'];

        if (! in_array($field, $allowed, true)) {
            return;
        }

        $item = TreatmentPlanItem::where('id', $itemId)
            ->where('treatment_plan_id', $this->plan->id)
            ->firstOrFail();

        $item->update([$field => $value]);

        if (in_array($field, ['quantity', 'unit_price'], true)) {
            $item->update(['line_total' => $item->fresh()->quantity * $item->fresh()->unit_price]);
            $this->plan->recalculateTotal();
            $this->plan->refresh();
        }

        $this->items = $this->plan->items()->orderBy('position')->get();
        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function removeItem(int $itemId): void
    {
        TreatmentPlanItem::where('id', $itemId)
            ->where('treatment_plan_id', $this->plan->id)
            ->delete();

        $this->items = $this->plan->items()->orderBy('position')->get();
        $this->plan->recalculateTotal();
        $this->plan->refresh();

        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function duplicateItem(int $itemId): void
    {
        $original = TreatmentPlanItem::where('id', $itemId)
            ->where('treatment_plan_id', $this->plan->id)
            ->firstOrFail();

        $copy = $original->replicate();
        $copy->position = $this->items->max('position') + 1;
        $copy->save();

        $this->items = $this->plan->items()->orderBy('position')->get();
        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function reorderItems(array $order): void
    {
        foreach ($order as $position => $itemId) {
            TreatmentPlanItem::where('id', $itemId)
                ->where('treatment_plan_id', $this->plan->id)
                ->update(['position' => $position]);
        }

        $this->items = $this->plan->items()->orderBy('position')->get();
        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function toggleOptional(int $itemId): void
    {
        $item = TreatmentPlanItem::where('id', $itemId)
            ->where('treatment_plan_id', $this->plan->id)
            ->firstOrFail();

        $item->update(['is_optional' => ! $item->is_optional]);

        $this->items = $this->plan->items()->orderBy('position')->get();
        $this->plan->recalculateTotal();
        $this->plan->refresh();

        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function refreshSuggestions(): void
    {
        $engine = app(ItemSuggestionEngine::class);
        $this->suggestions = $engine->suggestForDiagnosis($this->plan);
    }

    public function render()
    {
        $total = $this->items->where('is_optional', false)->sum('line_total');
        $optionalTotal = $this->items->where('is_optional', true)->sum('line_total');

        return view('livewire.plan-builder.treatment-step', [
            'total' => $total,
            'optionalTotal' => $optionalTotal,
        ]);
    }
}
