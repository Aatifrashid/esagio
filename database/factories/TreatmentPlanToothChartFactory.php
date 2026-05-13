<?php

namespace Database\Factories;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanToothChart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentPlanToothChart>
 */
class TreatmentPlanToothChartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $conditions = ['caries', 'fracture', 'missing', 'root_canal_required', 'periodontal_disease', 'wear', 'abrasion'];
        $plannedTreatments = ['extraction', 'implant', 'crown', 'veneer', 'root_canal', 'filling', 'whitening'];

        $toothNumbers = array_merge(
            range(11, 18), range(21, 28),
            range(31, 38), range(41, 48)
        );

        return [
            'treatment_plan_id' => TreatmentPlan::factory(),
            'tooth_number' => (string) $this->faker->randomElement($toothNumbers),
            'conditions' => $this->faker->optional(0.7)->passthrough(
                [$this->faker->randomElement($conditions)]
            ),
            'planned_treatments' => $this->faker->optional(0.8)->passthrough(
                [$this->faker->randomElement($plannedTreatments)]
            ),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
