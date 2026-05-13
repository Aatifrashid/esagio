<?php

namespace App\Services\PlanBuilder;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use App\Models\TreatmentPlanPhase;
use App\Models\TreatmentPlanSection;
use App\Models\TreatmentPlanToothChart;
use Illuminate\Support\Str;

class PlanCloner
{
    public function clone(TreatmentPlan $source, ?int $patientId = null): TreatmentPlan
    {
        $attributes = $source->only([
            'clinic_id', 'title', 'currency', 'language', 'notes_internal',
            'notes_to_patient', 'template_used_id', 'created_by', 'assigned_to',
        ]);

        $clone = TreatmentPlan::withoutGlobalScopes()->create(array_merge($attributes, [
            'patient_id' => $patientId ?? $source->patient_id,
            'status' => 'draft',
            'public_token' => Str::uuid(),
            'reference_number' => $this->generateReferenceNumber($source->clinic_id),
            'version' => 1,
            'viewed_count' => 0,
            'total_amount' => 0,
            'deposit_amount' => 0,
            'is_template' => false,
            'template_name' => null,
            'sent_at' => null,
            'viewed_at' => null,
            'accepted_at' => null,
            'declined_at' => null,
            'patient_signature' => null,
            'patient_signed_at' => null,
        ]));

        $this->cloneItems($source, $clone);
        $this->cloneSections($source, $clone);
        $this->cloneToothCharts($source, $clone);
        $this->clonePhases($source, $clone);

        $clone->recalculateTotal();

        return $clone->fresh();
    }

    public function saveAsTemplate(TreatmentPlan $plan, string $templateName): TreatmentPlan
    {
        $plan->update([
            'is_template' => true,
            'template_name' => $templateName,
        ]);

        return $plan->fresh();
    }

    public function createFromTemplate(TreatmentPlan $template, int $patientId, int $clinicId): TreatmentPlan
    {
        $plan = $this->clone($template, $patientId);

        $plan->update([
            'clinic_id' => $clinicId,
            'template_used_id' => $template->id,
            'is_template' => false,
        ]);

        return $plan->fresh();
    }

    private function cloneItems(TreatmentPlan $source, TreatmentPlan $target): void
    {
        foreach ($source->items as $item) {
            $attrs = $item->only([
                'treatment_template_id', 'name', 'description', 'quantity',
                'unit_price', 'line_total', 'material_id', 'variant_name',
                'position', 'notes', 'included_animation_clip_ids',
                'included_before_after_ids', 'tooth_positions', 'procedure_phase',
                'populated_fields', 'is_optional',
            ]);
            TreatmentPlanItem::create(array_merge($attrs, ['treatment_plan_id' => $target->id]));
        }
    }

    private function cloneSections(TreatmentPlan $source, TreatmentPlan $target): void
    {
        foreach ($source->sections as $section) {
            TreatmentPlanSection::create(array_merge(
                $section->only(['type', 'title', 'content', 'sort_order', 'is_visible']),
                ['treatment_plan_id' => $target->id]
            ));
        }
    }

    private function cloneToothCharts(TreatmentPlan $source, TreatmentPlan $target): void
    {
        foreach ($source->toothCharts as $chart) {
            TreatmentPlanToothChart::create(array_merge(
                $chart->only(['tooth_number', 'conditions', 'planned_treatments', 'notes']),
                ['treatment_plan_id' => $target->id]
            ));
        }
    }

    private function clonePhases(TreatmentPlan $source, TreatmentPlan $target): void
    {
        foreach ($source->phases as $phase) {
            TreatmentPlanPhase::create(array_merge(
                $phase->only([
                    'phase_number', 'name', 'description',
                    'estimated_duration_days', 'estimated_start_offset_days', 'sort_order',
                ]),
                ['treatment_plan_id' => $target->id]
            ));
        }
    }

    private function generateReferenceNumber(int $clinicId): string
    {
        $year = now()->year;
        $last = TreatmentPlan::withoutGlobalScopes()
            ->where('clinic_id', $clinicId)
            ->where('reference_number', 'like', "TP-{$year}-%")
            ->orderByDesc('id')
            ->value('reference_number');

        $next = $last ? ((int) substr($last, -5)) + 1 : 1;

        return 'TP-'.$year.'-'.str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}
