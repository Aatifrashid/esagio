<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\CrmActivity;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CrmActivity>
 */
class CrmActivityFactory extends Factory
{
    private static array $subjects = [
        'call_made' => [
            'Initial enquiry call',
            'Follow-up call regarding treatment plan',
            'Called to discuss implant options',
            'Outbound call -- checked on patient decision',
            'Called to confirm consultation appointment',
        ],
        'call_received' => [
            'Patient called with questions about pricing',
            'Inbound call -- patient requesting brochure',
            'Patient called to reschedule consultation',
            'Patient called after receiving plan',
            'Inbound enquiry about veneers',
        ],
        'email_sent' => [
            'Sent welcome email with clinic brochure',
            'Sent treatment plan PDF',
            'Sent before and after case studies',
            'Sent financing options information',
            'Sent appointment confirmation',
        ],
        'whatsapp_sent' => [
            'Sent WhatsApp introduction message',
            'Sent treatment plan via WhatsApp',
            'WhatsApp follow-up -- no response yet',
            'Sent clinic photos via WhatsApp',
            'Confirmed appointment via WhatsApp',
        ],
        'note' => [
            'Patient note added',
            'Internal note -- patient flagged as high priority',
            'Note: patient mentioned budget constraints',
            'Coordinator note after consultation review',
            'Note: patient travelling from abroad',
        ],
        'meeting_held' => [
            'In-clinic consultation completed',
            'Video consultation held via Zoom',
            'Follow-up meeting to review treatment plan',
            'Pre-treatment planning meeting',
            'Post-treatment review appointment',
        ],
        'plan_sent' => [
            'Full treatment plan sent via email',
            'Revised treatment plan sent',
            'Treatment plan with implant options sent',
            'Smile makeover plan sent',
            'Phase 1 treatment plan sent',
        ],
        'plan_viewed' => [
            'Patient viewed treatment plan (portal)',
            'Plan opened -- no response yet',
            'Patient viewed plan multiple times',
            'Plan viewed and shared with family member',
        ],
    ];

    private static array $descriptions = [
        'Patient expressed interest in proceeding with treatment. Will discuss with partner first.',
        'Answered questions about healing time and number of visits required.',
        'Patient was positive. Main concern is the total cost.',
        'Discussed finance options. Patient wants to think it over.',
        'Patient confirmed they are happy with the treatment plan.',
        'Patient asked about guarantees on implants and crowns.',
        'Very positive call. Patient wants to book before end of month.',
        'Patient is comparing quotes from two other clinics.',
        'Explained the full procedure step by step. Patient felt reassured.',
        'Patient requested photos of previous implant cases.',
        'No answer. Left voicemail and followed up via WhatsApp.',
        'Patient confirmed they have booked flights and accommodation.',
        'Discussed sedation options for anxious patient.',
        'Patient happy with outcome. Referred a friend.',
        'Follow-up -- patient still undecided. Sent additional case studies.',
    ];

    public function definition(): array
    {
        $type = $this->faker->randomElement([
            'call_made', 'call_received', 'email_sent', 'whatsapp_sent',
            'note', 'meeting_held', 'plan_sent', 'plan_viewed',
        ]);

        $subjects = self::$subjects[$type];

        return [
            'clinic_id' => Clinic::factory(),
            'patient_id' => Patient::factory(),
            'user_id' => null,
            'type' => $type,
            'subject' => $this->faker->randomElement($subjects),
            'description' => $this->faker->optional(0.75)->randomElement(self::$descriptions),
            'outcome' => $this->faker->optional(0.5)->randomElement([
                'positive', 'neutral', 'no_response', 'callback_requested', 'negative',
            ]),
            'duration_minutes' => in_array($type, ['call_made', 'call_received', 'meeting_held'])
                ? $this->faker->randomElement([5, 10, 15, 20, 30, 45, 60])
                : null,
            'occurred_at' => $this->faker->dateTimeBetween('-90 days', 'now'),
            'follow_up_date' => $this->faker->optional(0.4)->dateTimeBetween('now', '+14 days'),
            'follow_up_description' => $this->faker->optional(0.4)->randomElement([
                'Call back to check decision',
                'Send revised quote',
                'Chase for deposit payment',
                'Confirm appointment details',
            ]),
            'attachments' => null,
        ];
    }

    public function call(): static
    {
        return $this->state(['type' => 'call_made']);
    }

    public function email(): static
    {
        return $this->state(['type' => 'email_sent']);
    }

    public function meeting(): static
    {
        return $this->state([
            'type' => 'meeting_held',
            'duration_minutes' => $this->faker->randomElement([30, 45, 60, 90]),
        ]);
    }

    public function planSent(): static
    {
        return $this->state(['type' => 'plan_sent']);
    }
}
