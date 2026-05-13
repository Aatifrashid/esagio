<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\VideoConsultationSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VideoConsultationSession>
 */
class VideoConsultationSessionFactory extends Factory
{
    public function definition(): array
    {
        $status = $this->faker->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled', 'no_show']);
        $scheduledFor = $this->faker->dateTimeBetween('-30 days', '+30 days');
        $actualDuration = $status === 'completed' ? $this->faker->numberBetween(15, 60) : null;

        return [
            'clinic_id' => Clinic::factory(),
            'treatment_plan_id' => null,
            'patient_id' => Patient::factory(),
            'scheduled_for' => $scheduledFor,
            'duration_minutes' => $this->faker->randomElement([15, 30, 45, 60]),
            'daily_room_url' => $this->faker->optional(0.7)->url(),
            'daily_room_token_patient' => null,
            'daily_room_token_clinic' => null,
            'status' => $status,
            'recording_url' => $status === 'completed' ? $this->faker->optional(0.4)->url() : null,
            'recording_consent_given' => $this->faker->boolean(60),
            'notes' => $this->faker->optional(0.4)->sentence(),
            'conducted_by' => null,
            'actual_duration_minutes' => $actualDuration,
        ];
    }

    public function scheduled(): static
    {
        return $this->state([
            'status' => 'scheduled',
            'scheduled_for' => now()->addDays(3),
        ]);
    }

    public function completed(): static
    {
        return $this->state([
            'status' => 'completed',
            'scheduled_for' => now()->subDays(2),
            'actual_duration_minutes' => $this->faker->numberBetween(20, 50),
        ]);
    }
}
