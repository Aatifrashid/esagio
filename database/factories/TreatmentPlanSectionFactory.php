<?php

namespace Database\Factories;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentPlanSection>
 */
class TreatmentPlanSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['intro', 'treatment_summary', 'pricing', 'finance', 'aftercare', 'faq', 'custom'];

        return [
            'treatment_plan_id' => TreatmentPlan::factory(),
            'type' => $this->faker->randomElement($types),
            'title' => $this->faker->optional(0.7)->randomElement([
                'About Your Treatment',
                'Treatment Summary',
                'Investment and Pricing',
                'Aftercare Instructions',
                'Frequently Asked Questions',
                'Finance Options',
            ]),
            'content' => $this->faker->optional(0.6)->paragraph(),
            'sort_order' => $this->faker->numberBetween(0, 10),
            'is_visible' => $this->faker->boolean(90),
        ];
    }
}
