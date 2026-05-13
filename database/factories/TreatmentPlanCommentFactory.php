<?php

namespace Database\Factories;

use App\Models\TreatmentPlan;
use App\Models\TreatmentPlanComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentPlanComment>
 */
class TreatmentPlanCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPatient = $this->faker->boolean(40);

        $staffComments = [
            'Patient has been chasing a response. Needs follow-up call.',
            'Revised pricing agreed with patient after consultation.',
            'Please ensure the patient receives the finance brochure.',
            'Awaiting sign-off from the lead dentist before sending.',
        ];

        $patientComments = [
            'Could you clarify what the implant procedure involves?',
            'Is finance available for this treatment?',
            'I would like to proceed. How do I confirm?',
            'Can this be split into two phases?',
            'Thank you for the detailed plan.',
        ];

        return [
            'treatment_plan_id' => TreatmentPlan::factory(),
            'author_type' => $isPatient ? 'patient' : 'staff',
            'author_name' => $this->faker->name(),
            'author_id' => null,
            'content' => $isPatient
                ? $this->faker->randomElement($patientComments)
                : $this->faker->randomElement($staffComments),
            'is_internal' => $isPatient ? false : $this->faker->boolean(60),
            'parent_comment_id' => null,
        ];
    }
}
