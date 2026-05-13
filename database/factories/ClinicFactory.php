<?php

namespace Database\Factories;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Clinic> */
class ClinicFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company().' Dental';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(4),
            'email' => fake()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'country' => 'GB',
            'timezone' => 'Europe/London',
            'currency_default' => 'GBP',
            'language_default' => 'en',
            'plan_tier' => 'free',
            'is_active' => true,
        ];
    }

    public function starter(): static
    {
        return $this->state(['plan_tier' => 'starter']);
    }

    public function professional(): static
    {
        return $this->state(['plan_tier' => 'professional']);
    }

    public function agency(): static
    {
        return $this->state(['plan_tier' => 'agency']);
    }

    public function suspended(): static
    {
        return $this->state([
            'suspended_at' => now(),
            'suspended_reason' => 'Payment failed',
        ]);
    }

    public function onTrial(): static
    {
        return $this->state([
            'plan_tier' => 'professional',
            'trial_ends_at' => now()->addDays(14),
        ]);
    }
}
