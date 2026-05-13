<?php

namespace Database\Factories;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentPlanEvent>
 */
class TreatmentPlanEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventTypes = ['created', 'sent', 'viewed', 'accepted', 'declined', 'edited', 'signed', 'deposit_paid', 'commented'];

        return [
            'treatment_plan_id' => TreatmentPlan::factory(),
            'event_type' => $this->faker->randomElement($eventTypes),
            'user_id' => null,
            'patient_action' => $this->faker->boolean(30),
            'ip_address' => $this->faker->optional(0.7)->ipv4(),
            'user_agent' => $this->faker->optional(0.5)->userAgent(),
            'metadata' => null,
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
