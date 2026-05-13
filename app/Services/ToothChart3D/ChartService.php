<?php

namespace App\Services\ToothChart3D;

use App\Models\ToothChartCondition;
use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanToothChart;

class ChartService
{
    public const ADULT_TEETH = [
        11, 12, 13, 14, 15, 16, 17, 18,
        21, 22, 23, 24, 25, 26, 27, 28,
        31, 32, 33, 34, 35, 36, 37, 38,
        41, 42, 43, 44, 45, 46, 47, 48,
    ];

    public const PRIMARY_TEETH = [
        51, 52, 53, 54, 55,
        61, 62, 63, 64, 65,
        71, 72, 73, 74, 75,
        81, 82, 83, 84, 85,
    ];

    public const QUADRANT_MAP = [
        'upper_right' => [11, 12, 13, 14, 15, 16, 17, 18],
        'upper_left' => [21, 22, 23, 24, 25, 26, 27, 28],
        'lower_left' => [31, 32, 33, 34, 35, 36, 37, 38],
        'lower_right' => [41, 42, 43, 44, 45, 46, 47, 48],
    ];

    public function getChartData(TreatmentPlan $plan): array
    {
        $toothCharts = $plan->toothCharts()->get()->keyBy('tooth_number');
        $conditions = ToothChartCondition::all()->keyBy('code');

        $teeth = [];
        foreach (self::ADULT_TEETH as $number) {
            $chart = $toothCharts->get((string) $number);
            $teeth[$number] = [
                'number' => $number,
                'quadrant' => $this->getQuadrant($number),
                'name' => $this->getToothName($number),
                'conditions' => $chart?->conditions ?? [],
                'planned_treatments' => $chart?->planned_treatments ?? [],
                'notes' => $chart?->notes ?? '',
                'colours' => $this->getConditionColours($chart?->conditions ?? [], $conditions),
            ];
        }

        return $teeth;
    }

    public function setCondition(TreatmentPlan $plan, string $toothNumber, string $conditionCode): TreatmentPlanToothChart
    {
        $chart = TreatmentPlanToothChart::firstOrCreate(
            ['treatment_plan_id' => $plan->id, 'tooth_number' => $toothNumber],
            ['conditions' => [], 'planned_treatments' => []]
        );

        $conditions = $chart->conditions ?? [];
        if (! in_array($conditionCode, $conditions)) {
            $conditions[] = $conditionCode;
            $chart->update(['conditions' => $conditions]);
        }

        return $chart;
    }

    public function removeCondition(TreatmentPlan $plan, string $toothNumber, string $conditionCode): TreatmentPlanToothChart
    {
        $chart = TreatmentPlanToothChart::where('treatment_plan_id', $plan->id)
            ->where('tooth_number', $toothNumber)
            ->first();

        if (! $chart) {
            return new TreatmentPlanToothChart;
        }

        $conditions = array_values(array_diff($chart->conditions ?? [], [$conditionCode]));
        $chart->update(['conditions' => $conditions]);

        return $chart;
    }

    public function addPlannedTreatment(TreatmentPlan $plan, string $toothNumber, int $itemId): void
    {
        $chart = TreatmentPlanToothChart::firstOrCreate(
            ['treatment_plan_id' => $plan->id, 'tooth_number' => $toothNumber],
            ['conditions' => [], 'planned_treatments' => []]
        );

        $treatments = $chart->planned_treatments ?? [];
        if (! in_array($itemId, $treatments)) {
            $treatments[] = $itemId;
            $chart->update(['planned_treatments' => $treatments]);
        }
    }

    public function getConditionsForArch(TreatmentPlan $plan, string $arch): array
    {
        $teeth = $arch === 'upper'
            ? array_merge(self::QUADRANT_MAP['upper_right'], self::QUADRANT_MAP['upper_left'])
            : array_merge(self::QUADRANT_MAP['lower_left'], self::QUADRANT_MAP['lower_right']);

        return TreatmentPlanToothChart::where('treatment_plan_id', $plan->id)
            ->whereIn('tooth_number', array_map('strval', $teeth))
            ->get()
            ->toArray();
    }

    public function toSvgData(TreatmentPlan $plan): array
    {
        $chartData = $this->getChartData($plan);
        $svgTeeth = [];

        foreach ($chartData as $number => $tooth) {
            $svgTeeth[] = [
                'number' => $number,
                'x' => $this->getToothX($number),
                'y' => $this->getToothY($number),
                'fill' => $this->getPrimaryColour($tooth['conditions']),
                'conditions' => $tooth['conditions'],
                'label' => (string) $number,
            ];
        }

        return $svgTeeth;
    }

    public function convertNotation(int $fdiNumber, string $targetSystem): string
    {
        if ($targetSystem === 'universal') {
            return $this->fdiToUniversal($fdiNumber);
        }
        if ($targetSystem === 'palmer') {
            return $this->fdiToPalmer($fdiNumber);
        }

        return (string) $fdiNumber;
    }

    private function getQuadrant(int $number): string
    {
        return match (true) {
            $number >= 11 && $number <= 18 => 'upper_right',
            $number >= 21 && $number <= 28 => 'upper_left',
            $number >= 31 && $number <= 38 => 'lower_left',
            $number >= 41 && $number <= 48 => 'lower_right',
            default => 'unknown',
        };
    }

    private function getToothName(int $number): string
    {
        $position = $number % 10;
        $names = [
            1 => 'Central Incisor', 2 => 'Lateral Incisor', 3 => 'Canine',
            4 => 'First Premolar', 5 => 'Second Premolar', 6 => 'First Molar',
            7 => 'Second Molar', 8 => 'Third Molar',
        ];

        $side = ($number >= 11 && $number <= 18) || ($number >= 41 && $number <= 48) ? 'Right' : 'Left';
        $jaw = $number <= 28 ? 'Upper' : 'Lower';

        return "$jaw $side ".($names[$position] ?? 'Unknown');
    }

    private function getConditionColours(array $conditions, $allConditions): array
    {
        $colours = [];
        foreach ($conditions as $code) {
            $condition = $allConditions->get($code);
            if ($condition) {
                $colours[$code] = $condition->colour;
            }
        }

        return $colours;
    }

    private function getPrimaryColour(array $conditions): string
    {
        $priority = ['missing', 'to_extract', 'implant_planned', 'decayed', 'root_canal_needed', 'fractured'];
        foreach ($priority as $code) {
            if (in_array($code, $conditions)) {
                $condition = ToothChartCondition::where('code', $code)->first();

                return $condition?->colour ?? '#E5E7EB';
            }
        }

        return '#E5E7EB';
    }

    private function getToothX(int $number): int
    {
        $quadrant = intdiv($number, 10);
        $position = $number % 10;

        return match ($quadrant) {
            1 => 250 - ($position * 30),
            2 => 260 + ($position * 30),
            3 => 260 + ($position * 30),
            4 => 250 - ($position * 30),
            default => 0,
        };
    }

    private function getToothY(int $number): int
    {
        return $number <= 28 ? 50 : 150;
    }

    private function fdiToUniversal(int $fdi): string
    {
        $map = [
            18 => 1, 17 => 2, 16 => 3, 15 => 4, 14 => 5, 13 => 6, 12 => 7, 11 => 8,
            21 => 9, 22 => 10, 23 => 11, 24 => 12, 25 => 13, 26 => 14, 27 => 15, 28 => 16,
            38 => 17, 37 => 18, 36 => 19, 35 => 20, 34 => 21, 33 => 22, 32 => 23, 31 => 24,
            41 => 25, 42 => 26, 43 => 27, 44 => 28, 45 => 29, 46 => 30, 47 => 31, 48 => 32,
        ];

        return (string) ($map[$fdi] ?? $fdi);
    }

    private function fdiToPalmer(int $fdi): string
    {
        $position = $fdi % 10;
        $quadrant = intdiv($fdi, 10);
        $symbols = [1 => '┘', 2 => '└', 3 => '┐', 4 => '┌'];

        return $position.($symbols[$quadrant] ?? '');
    }
}
