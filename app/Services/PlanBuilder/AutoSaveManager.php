<?php

namespace App\Services\PlanBuilder;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\TreatmentPlanSection;
use App\Models\TreatmentPlanToothChart;

class AutoSaveManager
{
    public function save(TreatmentPlan $plan, array $data): TreatmentPlan
    {
        $wasSent = in_array($plan->status, ['sent', 'viewed', 'declined'], true);

        $plan->update(array_intersect_key($data, array_flip([
            'title', 'notes_internal', 'notes_to_patient', 'valid_until',
            'currency', 'language', 'assigned_to',
        ])));

        if (isset($data['items'])) {
            $this->saveItems($plan, $data['items']);
        }

        if (isset($data['sections'])) {
            $this->saveSections($plan, $data['sections']);
        }

        if (isset($data['tooth_chart'])) {
            $this->saveToothChart($plan, $data['tooth_chart']);
        }

        $plan->recalculateTotal();

        if ($wasSent) {
            $plan->incrementVersionOnEdit();
        }

        return $plan->fresh();
    }

    public function saveItems(TreatmentPlan $plan, array $items): void
    {
        $incoming = collect($items);
        $incomingIds = $incoming->pluck('id')->filter()->values()->all();

        $plan->items()->whereNotIn('id', $incomingIds)->delete();

        foreach ($items as $position => $data) {
            $data['treatment_plan_id'] = $plan->id;
            $data['position'] = $data['position'] ?? $position;

            if (! empty($data['id'])) {
                TreatmentPlanItem::where('id', $data['id'])
                    ->where('treatment_plan_id', $plan->id)
                    ->update(array_diff_key($data, ['id' => true]));
            } else {
                TreatmentPlanItem::create($data);
            }
        }
    }

    public function saveSections(TreatmentPlan $plan, array $sections): void
    {
        $incoming = collect($sections);
        $incomingIds = $incoming->pluck('id')->filter()->values()->all();

        $plan->sections()->whereNotIn('id', $incomingIds)->delete();

        foreach ($sections as $order => $data) {
            $data['treatment_plan_id'] = $plan->id;
            $data['sort_order'] = $data['sort_order'] ?? $order;

            if (! empty($data['id'])) {
                TreatmentPlanSection::where('id', $data['id'])
                    ->where('treatment_plan_id', $plan->id)
                    ->update(array_diff_key($data, ['id' => true]));
            } else {
                TreatmentPlanSection::create($data);
            }
        }
    }

    public function saveToothChart(TreatmentPlan $plan, array $chartData): void
    {
        foreach ($chartData as $entry) {
            TreatmentPlanToothChart::updateOrCreate(
                ['treatment_plan_id' => $plan->id, 'tooth_number' => $entry['tooth_number']],
                array_merge($entry, ['treatment_plan_id' => $plan->id])
            );
        }
    }
}
