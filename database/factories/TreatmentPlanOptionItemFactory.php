<?php

namespace Database\Factories;

use App\Models\TreatmentPlanOption;
use App\Models\TreatmentPlanOptionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentPlanOptionItem>
 */
class TreatmentPlanOptionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $treatments = [
            ['name' => 'Dental Implant (Straumann)', 'price' => 2200.00],
            ['name' => 'Implant Crown (Zirconia)', 'price' => 950.00],
            ['name' => 'Porcelain Veneer', 'price' => 750.00],
            ['name' => 'Composite Veneer', 'price' => 350.00],
            ['name' => 'Zirconia Crown', 'price' => 850.00],
            ['name' => 'Root Canal Treatment', 'price' => 650.00],
        ];

        $treatment = $this->faker->randomElement($treatments);
        $quantity = $this->faker->numberBetween(1, 4);
        $unitPrice = $treatment['price'];

        return [
            'treatment_plan_option_id' => TreatmentPlanOption::factory(),
            'treatment_template_id' => null,
            'name' => $treatment['name'],
            'description' => null,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => round($unitPrice * $quantity, 2),
            'material_id' => null,
            'variant_name' => null,
            'position' => $this->faker->numberBetween(0, 10),
            'notes' => null,
            'included_animation_clip_ids' => null,
            'included_before_after_ids' => null,
            'tooth_positions' => null,
            'procedure_phase' => null,
            'populated_fields' => null,
            'is_optional' => false,
        ];
    }
}
