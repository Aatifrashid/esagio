<?php

namespace App\Services\PlanBuilder;

use App\Models\TreatmentPlanItem;
use App\Models\TreatmentTemplate;

class PlanAutoPopulator
{
    private array $autoFields = ['description', 'notes', 'recovery_info', 'guarantee_text', 'legal_disclaimer'];

    public function populateFromTemplate(TreatmentPlanItem $item, TreatmentTemplate $template): TreatmentPlanItem
    {
        $populated = [];

        $item->description = $template->description_short;
        $populated[] = 'description';

        if ($template->procedure_steps) {
            $steps = is_array($template->procedure_steps) ? $template->procedure_steps : [];
            $item->notes = implode("\n", $steps);
            $populated[] = 'notes';
        }

        if ($template->recovery_info) {
            $item->recovery_info = $template->recovery_info;
            $populated[] = 'recovery_info';
        }

        if ($template->guarantee_text) {
            $item->guarantee_text = $template->guarantee_text;
            $populated[] = 'guarantee_text';
        }

        if ($template->legal_disclaimer) {
            $item->legal_disclaimer = $template->legal_disclaimer;
            $populated[] = 'legal_disclaimer';
        }

        $item->populated_fields = $populated;
        $item->save();

        return $item;
    }

    public function repopulate(TreatmentPlanItem $item, TreatmentTemplate $template): TreatmentPlanItem
    {
        $autoFilled = $item->populated_fields ?? [];

        if (in_array('description', $autoFilled, true)) {
            $item->description = $template->description_short;
        }

        if (in_array('notes', $autoFilled, true) && $template->procedure_steps) {
            $steps = is_array($template->procedure_steps) ? $template->procedure_steps : [];
            $item->notes = implode("\n", $steps);
        }

        if (in_array('recovery_info', $autoFilled, true) && $template->recovery_info) {
            $item->recovery_info = $template->recovery_info;
        }

        if (in_array('guarantee_text', $autoFilled, true) && $template->guarantee_text) {
            $item->guarantee_text = $template->guarantee_text;
        }

        if (in_array('legal_disclaimer', $autoFilled, true) && $template->legal_disclaimer) {
            $item->legal_disclaimer = $template->legal_disclaimer;
        }

        $item->save();

        return $item;
    }
}
