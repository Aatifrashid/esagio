<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\PriceList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PriceList>
 */
class PriceListFactory extends Factory
{
    public function definition(): array
    {
        $year = now()->year;

        return [
            'clinic_id' => Clinic::factory(),
            'name' => "Standard Price List {$year}",
            'currency' => 'GBP',
            'is_default' => false,
            'valid_from' => now()->startOfYear()->toDateString(),
            'valid_until' => now()->endOfYear()->toDateString(),
            'notes' => $this->faker->optional(0.4)->randomElement([
                'Prices inclusive of all laboratory fees.',
                'Prices subject to change following full clinical assessment.',
                'Finance options available -- 0% APR for qualifying patients.',
            ]),
        ];
    }

    public function default(): static
    {
        return $this->state([
            'name' => 'Standard Price List '.now()->year,
            'is_default' => true,
            'currency' => 'GBP',
        ]);
    }

    public function turkish(): static
    {
        return $this->state([
            'name' => 'Turkey Clinic Price List '.now()->year,
            'currency' => 'GBP',
            'notes' => 'Prices for treatments completed at our Istanbul partner clinic. Travel and accommodation packages available.',
        ]);
    }

    public function promotional(): static
    {
        $year = now()->year;

        return $this->state([
            'name' => "Summer Promotion {$year}",
            'is_default' => false,
            'valid_from' => now()->format('Y-06-01'),
            'valid_until' => now()->format('Y-08-31'),
            'notes' => 'Promotional pricing valid for consultations booked and confirmed before 31 August.',
        ]);
    }
}
