<?php

namespace Database\Factories;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentPlanOption>
 */
class TreatmentPlanOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $labels = [
            'Standard Option',
            'Premium Option',
            'Budget Option',
            'Phased Treatment',
            'Express Treatment',
        ];

        return [
            'treatment_plan_id' => TreatmentPlan::factory(),
            'option_label' => $this->faker->randomElement($labels),
            'description' => $this->faker->optional(0.6)->sentence(),
            'is_recommended' => $this->faker->boolean(30),
            'total_amount' => $this->faker->randomFloat(2, 1000, 15000),
            'sort_order' => $this->faker->numberBetween(0, 5),
            'savings_vs_premium' => $this->faker->optional(0.4)->randomFloat(2, 200, 3000),
        ];
    }
}
