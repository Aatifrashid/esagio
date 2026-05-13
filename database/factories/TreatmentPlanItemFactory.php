<?php

namespace Database\Factories;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentPlanItem>
 */
class TreatmentPlanItemFactory extends Factory
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
            ['name' => 'Bone Graft (Autologous)', 'price' => 650.00],
            ['name' => 'Sinus Lift', 'price' => 1200.00],
            ['name' => 'Porcelain Veneer', 'price' => 750.00],
            ['name' => 'Composite Veneer', 'price' => 350.00],
            ['name' => 'Teeth Whitening (Zoom)', 'price' => 450.00],
            ['name' => 'Full Arch (All-on-4)', 'price' => 9500.00],
            ['name' => 'Invisalign Comprehensive', 'price' => 3500.00],
            ['name' => 'Root Canal Treatment', 'price' => 650.00],
            ['name' => 'Extraction (Surgical)', 'price' => 280.00],
            ['name' => 'Periodontal Deep Clean', 'price' => 400.00],
            ['name' => 'Zirconia Crown', 'price' => 850.00],
            ['name' => 'Composite Bonding (per tooth)', 'price' => 280.00],
            ['name' => 'Consultation and X-rays', 'price' => 150.00],
        ];

        $treatment = $this->faker->randomElement($treatments);
        $quantity = $this->faker->numberBetween(1, 4);
        $unitPrice = $treatment['price'];
        $lineTotal = round($unitPrice * $quantity, 2);

        return [
            'treatment_plan_id' => TreatmentPlan::factory(),
            'treatment_template_id' => null,
            'name' => $treatment['name'],
            'description' => $this->faker->optional(0.5)->randomElement([
                'Includes abutment and surgical kit.',
                'Premium ceramic material with 10-year guarantee.',
                'Includes two follow-up appointments.',
                'Guided implant surgery with surgical stent.',
            ]),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $lineTotal,
            'material_id' => null,
            'variant_name' => null,
            'position' => $this->faker->numberBetween(0, 10),
            'notes' => null,
            'included_animation_clip_ids' => null,
            'included_before_after_ids' => null,
            'tooth_positions' => $this->faker->optional(0.5)->passthrough(
                json_encode([$this->faker->numberBetween(11, 48)])
            ),
            'procedure_phase' => $this->faker->optional(0.4)->randomElement(['phase_1', 'phase_2', 'phase_3']),
            'populated_fields' => null,
            'is_optional' => $this->faker->boolean(20),
        ];
    }
}
