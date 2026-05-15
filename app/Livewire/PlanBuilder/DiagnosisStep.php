<?php

namespace App\Livewire\PlanBuilder;

use App\Models\ToothChartCondition;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanToothChart;
use Livewire\Component;

class DiagnosisStep extends Component
{
    public TreatmentPlan $plan;

    public string $diagnosisText = '';

    public array $conditionTags = [];

    public array $toothChartData = [];

    public array $selectedTeeth = [];

    public ?string $activeCondition = null;

    public function mount(TreatmentPlan $plan): void
    {
        $this->plan = $plan;
        $this->diagnosisText = $plan->notes_to_patient ?? '';

        $this->toothChartData = $plan->toothCharts
            ->keyBy('tooth_number')
            ->map(fn ($c) => [
                'tooth_number' => $c->tooth_number,
                'conditions' => $c->conditions ?? [],
                'notes' => $c->notes ?? '',
            ])
            ->toArray();

        $this->refreshConditionTags();
    }

    public function toggleTooth(string $toothNumber): void
    {
        if (in_array($toothNumber, $this->selectedTeeth, true)) {
            $this->selectedTeeth = array_values(array_filter($this->selectedTeeth, fn ($t) => $t !== $toothNumber));
        } else {
            $this->selectedTeeth[] = $toothNumber;
        }

        if ($this->activeCondition && in_array($toothNumber, $this->selectedTeeth, true)) {
            $this->applyConditionToTooth($toothNumber, $this->activeCondition);
        }
    }

    public function selectCondition(string $conditionCode): void
    {
        if ($this->activeCondition === $conditionCode) {
            $this->activeCondition = null;

            return;
        }

        $this->activeCondition = $conditionCode;

        foreach ($this->selectedTeeth as $tooth) {
            $this->applyConditionToTooth($tooth, $conditionCode);
        }
    }

    public function applyConditionToTeeth(string $conditionCode): void
    {
        foreach ($this->selectedTeeth as $tooth) {
            $this->applyConditionToTooth($tooth, $conditionCode);
        }
        $this->refreshConditionTags();
    }

    private function applyConditionToTooth(string $toothNumber, string $conditionCode): void
    {
        if (! isset($this->toothChartData[$toothNumber])) {
            $this->toothChartData[$toothNumber] = [
                'tooth_number' => $toothNumber,
                'conditions' => [],
                'notes' => '',
            ];
        }

        if (! in_array($conditionCode, $this->toothChartData[$toothNumber]['conditions'], true)) {
            $this->toothChartData[$toothNumber]['conditions'][] = $conditionCode;
        }

        $this->refreshConditionTags();
    }

    public function removeConditionFromTooth(string $toothNumber, string $conditionCode): void
    {
        if (isset($this->toothChartData[$toothNumber])) {
            $this->toothChartData[$toothNumber]['conditions'] = array_values(
                array_filter(
                    $this->toothChartData[$toothNumber]['conditions'],
                    fn ($c) => $c !== $conditionCode
                )
            );

            if (empty($this->toothChartData[$toothNumber]['conditions']) && empty($this->toothChartData[$toothNumber]['notes'])) {
                unset($this->toothChartData[$toothNumber]);
            }
        }

        $this->refreshConditionTags();
    }

    public function clearSelectedTeeth(): void
    {
        $this->selectedTeeth = [];
        $this->activeCondition = null;
    }

    public function clearAllConditions(): void
    {
        $this->toothChartData = [];
        $this->selectedTeeth = [];
        $this->activeCondition = null;
        $this->refreshConditionTags();
    }

    public function selectAllUpper(): void
    {
        $upper = ['18','17','16','15','14','13','12','11','21','22','23','24','25','26','27','28'];
        $this->selectedTeeth = array_values(array_unique(array_merge($this->selectedTeeth, $upper)));
    }

    public function selectAllLower(): void
    {
        $lower = ['48','47','46','45','44','43','42','41','31','32','33','34','35','36','37','38'];
        $this->selectedTeeth = array_values(array_unique(array_merge($this->selectedTeeth, $lower)));
    }

    public function selectAll(): void
    {
        $this->selectAllUpper();
        $this->selectAllLower();
    }

    private function refreshConditionTags(): void
    {
        $this->conditionTags = collect($this->toothChartData)
            ->flatMap(fn ($t) => $t['conditions'])
            ->unique()
            ->values()
            ->toArray();
    }

    public function saveDiagnosis(): void
    {
        $this->plan->update(['notes_to_patient' => $this->diagnosisText]);

        foreach ($this->toothChartData as $entry) {
            TreatmentPlanToothChart::updateOrCreate(
                [
                    'treatment_plan_id' => $this->plan->id,
                    'tooth_number' => $entry['tooth_number'],
                ],
                [
                    'conditions' => $entry['conditions'],
                    'notes' => $entry['notes'] ?? '',
                ]
            );
        }

        TreatmentPlanToothChart::where('treatment_plan_id', $this->plan->id)
            ->whereNotIn('tooth_number', array_keys($this->toothChartData))
            ->delete();

        $this->dispatch('plan-updated')->to(Builder::class);
        $this->dispatch('diagnosis-saved');
    }

    public function render()
    {
        $availableConditions = ToothChartCondition::orderBy('sort_order')
            ->whereNotIn('code', ['implant_planned', 'veneer_planned', 'to_extract', 'root_canal_needed'])
            ->get(['code', 'name', 'colour'])
            ->map(fn ($c) => ['code' => $c->code, 'label' => $c->name, 'colour' => $c->colour])
            ->toArray();

        return view('livewire.plan-builder.diagnosis-step', [
            'availableConditions' => $availableConditions,
        ]);
    }
}
