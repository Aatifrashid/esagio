<?php

namespace App\Services\AnimationStitcher;

use App\Models\AnimationClip;
use App\Models\TreatmentPlan;
use Illuminate\Support\Collection;

class ClipSelector
{
    /**
     * Select an ordered collection of AnimationClip models for the given treatment plan.
     *
     * Matching priority:
     *  1. Clips that match both treatment_category_id and at least one tooth position.
     *  2. Clips that match treatment_category_id only.
     *  3. Clips referenced directly via item->included_animation_clip_ids.
     *
     * Clips are returned in a logical procedure order derived from sort_order and
     * procedure type (diagnosis first, extractions, surgical, then restorative).
     */
    public function selectForPlan(TreatmentPlan $plan): Collection
    {
        $plan->loadMissing('items');

        $selectedClipIds = collect();
        $procedureOrder = [
            'diagnosis' => 0,
            'extraction' => 1,
            'surgical' => 2,
            'implant' => 3,
            'root_canal' => 4,
            'crown' => 5,
            'bridge' => 6,
            'veneer' => 7,
            'filling' => 8,
            'whitening' => 9,
            'general' => 99,
        ];

        foreach ($plan->items as $item) {
            if ($item->treatment_category_id) {
                // Priority 1: category + tooth positions
                if (! empty($item->tooth_positions)) {
                    $clips = AnimationClip::where('treatment_category_id', $item->treatment_category_id)
                        ->where(function ($q) use ($item) {
                            foreach ($item->tooth_positions as $pos) {
                                $q->orWhereJsonContains('tooth_positions', $pos);
                            }
                        })
                        ->pluck('id');

                    if ($clips->isNotEmpty()) {
                        $selectedClipIds = $selectedClipIds->merge($clips);

                        continue;
                    }
                }

                // Priority 2: category only
                $clips = AnimationClip::where('treatment_category_id', $item->treatment_category_id)
                    ->pluck('id');

                if ($clips->isNotEmpty()) {
                    $selectedClipIds = $selectedClipIds->merge($clips);

                    continue;
                }
            }

            // Priority 3: explicit clip IDs on the item
            if (! empty($item->included_animation_clip_ids)) {
                $selectedClipIds = $selectedClipIds->merge(
                    collect($item->included_animation_clip_ids)
                );
            }
        }

        $uniqueIds = $selectedClipIds->unique()->values();

        if ($uniqueIds->isEmpty()) {
            return collect();
        }

        return AnimationClip::whereIn('id', $uniqueIds)
            ->get()
            ->sortBy(function (AnimationClip $clip) use ($procedureOrder) {
                $typeOrder = $procedureOrder[$clip->procedure_type] ?? 99;

                return sprintf('%03d_%04d', $typeOrder, $clip->sort_order ?? 0);
            })
            ->values();
    }
}
