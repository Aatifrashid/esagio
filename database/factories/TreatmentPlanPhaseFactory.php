<?php

namespace Database\Factories;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanPhase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentPlanPhase>
 */
class TreatmentPlanPhaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $phaseCounter = 0;
        $phaseCounter++;

        $phaseNames = [
            'Consultation and Diagnostics',
            'Preparatory Treatment',
            'Surgical Phase',
            'Healing and Osseointegration',
            'Restorative Phase',
            'Final Fitting and Review',
        ];

        return [
            'treatment_plan_id' => TreatmentPlan::factory(),
            'phase_number' => $phaseCounter,
            'name' => $this->faker->randomElement($phaseNames),
            'description' => $this->faker->optional(0.6)->sentence(),
            'estimated_duration_days' => $this->faker->optional(0.7)->numberBetween(7, 90),
            'estimated_start_offset_days' => $this->faker->numberBetween(0, 120),
            'sort_order' => $phaseCounter - 1,
        ];
    }
}
