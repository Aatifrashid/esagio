<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\CrmTask;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CrmTask>
 */
class CrmTaskFactory extends Factory
{
    private static array $taskTitles = [
        'Follow up on implant consultation',
        'Send treatment plan to patient',
        'Chase outstanding quote decision',
        'Book consultation appointment',
        'Send before and after case studies',
        'Follow up after consultation -- no response',
        'Send revised implant plan with finance options',
        'Confirm deposit payment received',
        'Book All-on-4 pre-treatment assessment',
        'Send post-treatment care instructions',
        'Follow up on veneer enquiry',
        'Chase patient for panoramic X-ray results',
        'Send smile makeover brochure',
        'Confirm travel and accommodation details',
        'Arrange interpreter for Turkish-speaking patient',
        'Follow up on orthodontic treatment enquiry',
        'Send teeth whitening protocol information',
        'Chase signed consent forms',
        'Book follow-up check-up appointment',
        'Update patient record with new contact number',
        'Confirm payment plan agreement',
        'Send 3D smile preview link to patient',
        'Follow up after failed implant enquiry',
        'Schedule review for periodontal patient',
        'Send referral thank-you note to referring patient',
    ];

    private static array $taskDescriptions = [
        'Patient has not responded since the initial consultation. Send a follow-up email and WhatsApp.',
        'Treatment plan is ready. Send PDF via email and notify patient via WhatsApp.',
        'Patient requested time to discuss with spouse. Check back after one week.',
        'Patient confirmed interest. Book them in for a full assessment.',
        'Patient asked to see examples of previous work before committing.',
        'Confirm the deposit has cleared and issue a receipt.',
        'Obtain signed consent forms before proceeding with surgery.',
        'Patient is travelling from Istanbul. Confirm all appointments fit within their trip.',
        'Send a detailed breakdown of the All-on-4 procedure steps.',
        'Patient had concerns about recovery time. Provide written aftercare guide.',
    ];

    public function definition(): array
    {
        $status = $this->faker->randomElement(['open', 'in_progress', 'done']);

        return [
            'clinic_id' => Clinic::factory(),
            'patient_id' => Patient::factory(),
            'assigned_to' => null,
            'created_by' => null,
            'title' => $this->faker->randomElement(self::$taskTitles),
            'description' => $this->faker->optional(0.6)->randomElement(self::$taskDescriptions),
            'due_date' => $this->faker->optional(0.8)->dateTimeBetween('-7 days', '+30 days'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => $status,
            'completed_at' => $status === 'done'
                ? $this->faker->dateTimeBetween('-30 days', 'now')
                : null,
            'related_to_type' => null,
            'related_to_id' => null,
        ];
    }

    public function open(): static
    {
        return $this->state(['status' => 'open', 'completed_at' => null]);
    }

    public function done(): static
    {
        return $this->state([
            'status' => 'done',
            'completed_at' => $this->faker->dateTimeBetween('-14 days', 'now'),
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(['priority' => 'high']);
    }

    public function overdue(): static
    {
        return $this->state([
            'status' => 'open',
            'due_date' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
        ]);
    }
}
