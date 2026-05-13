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

    public ?string $activeTooth = null;

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

        $this->conditionTags = collect($this->toothChartData)
            ->flatMap(fn ($t) => $t['conditions'])
            ->unique()
            ->values()
            ->toArray();
    }

    public function selectTooth(string $toothNumber): void
    {
        $this->activeTooth = ($this->activeTooth === $toothNumber) ? null : $toothNumber;
    }

    public function addCondition(string $toothNumber, string $conditionCode): void
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

    public function removeCondition(string $toothNumber, string $conditionCode): void
    {
        if (isset($this->toothChartData[$toothNumber])) {
            $this->toothChartData[$toothNumber]['conditions'] = array_values(
                array_filter(
                    $this->toothChartData[$toothNumber]['conditions'],
                    fn ($c) => $c !== $conditionCode
                )
            );
        }

        $this->refreshConditionTags();
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

        $this->dispatch('plan-updated', $this->toothChartData);
    }

    public function render()
    {
        $availableConditions = ToothChartCondition::all(['code', 'label', 'colour'])->toArray();

        return view('livewire.plan-builder.diagnosis-step', [
            'availableConditions' => $availableConditions,
        ]);
    }
}
