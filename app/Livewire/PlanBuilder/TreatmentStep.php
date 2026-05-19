<?php

namespace App\Livewire\PlanBuilder;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\TreatmentPlanPhase;
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

    public bool $showVisits = false;

    public ?int $activeItemId = null;

    public int $visitCount = 2;

    public array $visitAssignments = [];

    public function mount(TreatmentPlan $plan): void
    {
        $this->plan = $plan;
        $this->items = $plan->items()->orderBy('position')->get();
        $this->phases = $plan->phases()->orderBy('sort_order')->get();
        $this->recentTemplates = TreatmentTemplate::where('clinic_id', $plan->clinic_id)
            ->orderByDesc('usage_count')
            ->limit(10)
            ->get(['id', 'name', 'description_short', 'code'])
            ->toArray();

        $this->loadVisitAssignments();
        $this->refreshSuggestions();
    }

    private function loadVisitAssignments(): void
    {
        if ($this->phases->isNotEmpty()) {
            $this->showVisits = true;
            $this->visitCount = $this->phases->count();
            foreach ($this->phases as $phase) {
                $this->visitAssignments[$phase->id] = [
                    'name' => $phase->name,
                    'items' => $this->items->filter(fn ($item) => $item->procedure_phase === (string) $phase->phase_number)->pluck('id')->toArray(),
                ];
            }
        }
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

        $nextPosition = ($this->items->max('position') ?? 0) + 1;

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
        $nextPosition = ($this->items->max('position') ?? 0) + 1;

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
        $allowed = ['name', 'description', 'quantity', 'unit_price', 'notes', 'procedure_phase', 'is_optional', 'tooth_positions'];

        if (! in_array($field, $allowed, true)) {
            return;
        }

        $item = TreatmentPlanItem::where('id', $itemId)
            ->where('treatment_plan_id', $this->plan->id)
            ->firstOrFail();

        $item->update([$field => $value]);

        if ($field === 'tooth_positions' && is_array($value)) {
            $item->update(['quantity' => max(1, count($value))]);
        }

        if (in_array($field, ['quantity', 'unit_price', 'tooth_positions'], true)) {
            $item->refresh();
            $item->update(['line_total' => $item->quantity * $item->unit_price]);
            $this->plan->recalculateTotal();
            $this->plan->refresh();
        }

        $this->items = $this->plan->items()->orderBy('position')->get();
        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function assignTeethToItem(int $itemId, array $teeth): void
    {
        $this->updateItem($itemId, 'tooth_positions', $teeth);
    }

    public function setActiveItem(?int $itemId): void
    {
        $this->activeItemId = $this->activeItemId === $itemId ? null : $itemId;
    }

    public function toggleToothForItem(string $tooth): void
    {
        if (! $this->activeItemId) {
            return;
        }

        $item = TreatmentPlanItem::where('id', $this->activeItemId)
            ->where('treatment_plan_id', $this->plan->id)
            ->first();

        if (! $item) {
            return;
        }

        $positions = $item->tooth_positions ?? [];

        if (in_array($tooth, $positions)) {
            $positions = array_values(array_diff($positions, [$tooth]));
        } else {
            $positions[] = $tooth;
        }

        $this->updateItem($this->activeItemId, 'tooth_positions', $positions);
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
        $copy->position = ($this->items->max('position') ?? 0) + 1;
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

    public function toggleVisits(): void
    {
        $this->showVisits = ! $this->showVisits;

        if ($this->showVisits && $this->phases->isEmpty()) {
            $this->createVisits();
        }
    }

    public function setVisitCount(int $count): void
    {
        $count = max(1, min(5, $count));
        $this->visitCount = $count;

        TreatmentPlanPhase::where('treatment_plan_id', $this->plan->id)->delete();
        $this->createVisits();
    }

    private function createVisits(): void
    {
        for ($i = 1; $i <= $this->visitCount; $i++) {
            TreatmentPlanPhase::create([
                'treatment_plan_id' => $this->plan->id,
                'phase_number' => $i,
                'name' => 'Visit '.$i,
                'sort_order' => $i,
            ]);
        }

        $this->phases = $this->plan->phases()->orderBy('sort_order')->get();
        $this->loadVisitAssignments();
        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function assignItemToVisit(int $itemId, int $phaseNumber): void
    {
        $item = TreatmentPlanItem::where('id', $itemId)
            ->where('treatment_plan_id', $this->plan->id)
            ->firstOrFail();

        $item->update(['procedure_phase' => (string) $phaseNumber]);

        $this->items = $this->plan->items()->orderBy('position')->get();
        $this->loadVisitAssignments();
        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function removeItemFromVisit(int $itemId): void
    {
        $item = TreatmentPlanItem::where('id', $itemId)
            ->where('treatment_plan_id', $this->plan->id)
            ->firstOrFail();

        $item->update(['procedure_phase' => null]);

        $this->items = $this->plan->items()->orderBy('position')->get();
        $this->loadVisitAssignments();
        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function updateVisitName(int $phaseId, string $name): void
    {
        TreatmentPlanPhase::where('id', $phaseId)
            ->where('treatment_plan_id', $this->plan->id)
            ->update(['name' => $name]);

        $this->phases = $this->plan->phases()->orderBy('sort_order')->get();
        $this->dispatch('plan-updated')->to(Builder::class);
    }

    public function render()
    {
        $total = $this->items->where('is_optional', false)->sum('line_total');
        $optionalTotal = $this->items->where('is_optional', true)->sum('line_total');

        $toothChartData = $this->plan->toothCharts
            ->keyBy('tooth_number')
            ->map(fn ($c) => [
                'conditions' => $c->conditions ?? [],
            ])
            ->toArray();

        $unassignedItems = $this->items->filter(fn ($item) => empty($item->procedure_phase));

        return view('livewire.plan-builder.treatment-step', [
            'total' => $total,
            'optionalTotal' => $optionalTotal,
            'toothChartData' => $toothChartData,
            'unassignedItems' => $unassignedItems,
        ]);
    }
}
