<?php

namespace App\Services\PlanBuilder;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;

class ItemSuggestionEngine
{
    private array $conditionMap = [
        'missing' => ['name' => 'Dental Implant', 'reason' => 'Tooth is missing; implant placement recommended.'],
        'decayed' => ['name' => 'Zirconia Crown', 'reason' => 'Tooth is decayed; crown or filling recommended.'],
        'root_canal_needed' => ['name' => 'Root Canal Treatment', 'reason' => 'Tooth requires endodontic treatment.'],
        'to_extract' => ['name' => 'Simple Tooth Extraction', 'reason' => 'Tooth is marked for extraction.'],
        'veneer_planned' => ['name' => 'Porcelain Veneer', 'reason' => 'Tooth is marked for veneer placement.'],
    ];

    private array $companionMap = [
        'implant' => [
            ['name' => 'Implant Abutment', 'reason' => 'Abutment required to connect implant fixture to crown.'],
            ['name' => 'Implant Crown (Zirconia)', 'reason' => 'Crown needed to complete the implant restoration.'],
            ['name' => 'Temporary Crown', 'reason' => 'Provisional crown during osseointegration period.'],
            ['name' => 'Bone Graft Assessment', 'reason' => 'Bone volume assessment recommended prior to implant placement.'],
        ],
        'crown' => [
            ['name' => 'Core Build-Up', 'reason' => 'Core build-up may be required before crown preparation.'],
        ],
        'root canal' => [
            ['name' => 'Zirconia Crown', 'reason' => 'Crown recommended to protect the root-treated tooth.'],
        ],
    ];

    public function suggestForDiagnosis(TreatmentPlan $plan): array
    {
        $suggestions = [];
        $charts = $plan->toothCharts()->get();

        $upperMissing = [];
        $lowerMissing = [];

        foreach ($charts as $chart) {
            $tooth = (int) $chart->tooth_number;
            $conditions = (array) ($chart->conditions ?? []);

            foreach ($conditions as $condition) {
                $condition = strtolower($condition);

                if (isset($this->conditionMap[$condition])) {
                    $entry = $this->conditionMap[$condition];
                    $suggestions[] = [
                        'template_id' => null,
                        'name' => $entry['name'],
                        'reason' => $entry['reason'],
                        'tooth_positions' => [$tooth],
                    ];
                }

                if ($condition === 'missing') {
                    if ($tooth >= 11 && $tooth <= 28) {
                        $upperMissing[] = $tooth;
                    } elseif ($tooth >= 31 && $tooth <= 48) {
                        $lowerMissing[] = $tooth;
                    }
                }
            }
        }

        if (count($upperMissing) >= 4) {
            $suggestions[] = [
                'template_id' => null,
                'name' => 'All-on-4 Dental Implants',
                'reason' => '4 or more missing teeth in the upper arch; All-on-4 package recommended.',
                'tooth_positions' => $upperMissing,
            ];
        }

        if (count($lowerMissing) >= 4) {
            $suggestions[] = [
                'template_id' => null,
                'name' => 'All-on-4 Dental Implants',
                'reason' => '4 or more missing teeth in the lower arch; All-on-4 package recommended.',
                'tooth_positions' => $lowerMissing,
            ];
        }

        return $suggestions;
    }

    public function suggestCompanions(TreatmentPlanItem $item): array
    {
        $name = strtolower($item->name);
        $companions = [];

        foreach ($this->companionMap as $keyword => $entries) {
            if (str_contains($name, $keyword)) {
                foreach ($entries as $entry) {
                    $companions[] = array_merge([
                        'template_id' => null,
                        'tooth_positions' => $item->tooth_positions ?? [],
                    ], $entry);
                }
            }
        }

        return $companions;
    }
}
