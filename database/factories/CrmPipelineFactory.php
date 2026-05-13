<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\CrmPipeline;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CrmPipeline>
 */
class CrmPipelineFactory extends Factory
{
    protected static int $sequence = 0;

    public function definition(): array
    {
        $names = [
            'Consultation Pipeline',
            'Treatment Pipeline',
            'Recall Pipeline',
            'Referral Pipeline',
            'VIP Pipeline',
        ];

        self::$sequence++;

        return [
            'clinic_id' => Clinic::factory(),
            'name' => $this->faker->randomElement($names),
            'description' => $this->faker->optional(0.6)->randomElement([
                'Manages new patient enquiries through to a booked consultation.',
                'Tracks active treatment cases from plan acceptance to completion.',
                'Follows up with existing patients due for a recall appointment.',
                'Pipeline for patients referred by existing patients or GPs.',
                'Dedicated pipeline for high-value VIP patients.',
            ]),
            'is_default' => false,
            'sort_order' => self::$sequence,
        ];
    }

    public function default(): static
    {
        return $this->state([
            'name' => 'Consultation Pipeline',
            'is_default' => true,
            'sort_order' => 0,
        ]);
    }

    public function treatment(): static
    {
        return $this->state([
            'name' => 'Treatment Pipeline',
            'is_default' => false,
        ]);
    }

    public function recall(): static
    {
        return $this->state([
            'name' => 'Recall Pipeline',
            'is_default' => false,
        ]);
    }
}
