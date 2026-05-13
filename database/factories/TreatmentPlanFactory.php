<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\TreatmentPlan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TreatmentPlan>
 */
class TreatmentPlanFactory extends Factory
{
    public function definition(): array
    {
        static $sequence = 0;
        $sequence++;

        $year = now()->year;
        $referenceNumber = 'TP-'.$year.'-'.str_pad($sequence, 5, '0', STR_PAD_LEFT);

        $titles = [
            'Full Mouth Rehabilitation',
            'Dental Implant Treatment',
            'Smile Makeover',
            'Orthodontic Treatment',
            'Composite Bonding and Whitening',
            'All-on-4 Implants',
            'Veneers and Whitening Package',
            'Periodontal Treatment Plan',
            'Single Implant and Crown',
            'Invisalign Treatment',
        ];

        $status = $this->faker->randomElement(['draft', 'sent', 'viewed', 'accepted', 'declined']);

        $sentAt = null;
        $viewedAt = null;
        $acceptedAt = null;
        $declinedAt = null;

        if (in_array($status, ['sent', 'viewed', 'accepted', 'declined'])) {
            $sentAt = $this->faker->dateTimeBetween('-30 days', '-5 days');
        }
        if (in_array($status, ['viewed', 'accepted', 'declined'])) {
            $viewedAt = $this->faker->dateTimeBetween($sentAt, '-1 day');
        }
        if ($status === 'accepted') {
            $acceptedAt = $this->faker->dateTimeBetween($viewedAt, 'now');
        }
        if ($status === 'declined') {
            $declinedAt = $this->faker->dateTimeBetween($viewedAt, 'now');
        }

        return [
            'clinic_id' => Clinic::factory(),
            'patient_id' => Patient::factory(),
            'reference_number' => $referenceNumber,
            'title' => $this->faker->randomElement($titles),
            'status' => $status,
            'created_by' => null,
            'assigned_to' => null,
            'signed_by_dentist_id' => null,
            'currency' => 'GBP',
            'exchange_rate_snapshot' => null,
            'language' => 'en',
            'valid_until' => $this->faker->optional(0.6)->dateTimeBetween('now', '+60 days'),
            'sent_at' => $sentAt,
            'viewed_at' => $viewedAt,
            'viewed_count' => $viewedAt ? $this->faker->numberBetween(1, 5) : 0,
            'last_viewed_at' => $viewedAt,
            'accepted_at' => $acceptedAt,
            'declined_at' => $declinedAt,
            'decline_reason' => $declinedAt ? $this->faker->randomElement([
                'Price too high',
                'Going with another clinic',
                'Decided to delay treatment',
                'Not comfortable with the treatment plan',
            ]) : null,
            'patient_signature' => $acceptedAt ? 'data:image/png;base64,iVBORw0KGgo=' : null,
            'patient_signed_at' => $acceptedAt,
            'patient_ip_at_signing' => $acceptedAt ? $this->faker->ipv4() : null,
            'patient_user_agent_at_signing' => $acceptedAt ? 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)' : null,
            'total_amount' => $this->faker->randomFloat(2, 500, 18000),
            'deposit_amount' => $this->faker->randomFloat(2, 200, 2000),
            'deposit_paid_at' => null,
            'deposit_payment_intent_id' => null,
            'public_token' => Str::uuid(),
            'public_password' => null,
            'pdf_path' => null,
            'version' => 1,
            'notes_internal' => $this->faker->optional(0.4)->randomElement([
                'Patient is a referral from Dr Johnson. Handle with care.',
                'Previous quotes were provided last year. Updated pricing.',
                'Patient has expressed concern about anaesthesia.',
            ]),
            'notes_to_patient' => $this->faker->optional(0.5)->randomElement([
                'Please review each treatment item carefully and contact us if you have any questions.',
                'This plan is valid for 60 days. Finance options are available upon request.',
                'We look forward to welcoming you to our clinic.',
            ]),
            'video_consultation_room_id' => null,
            'template_used_id' => null,
            'is_template' => false,
            'template_name' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => 'draft', 'sent_at' => null]);
    }

    public function sent(): static
    {
        return $this->state([
            'status' => 'sent',
            'sent_at' => now()->subDays(3),
            'viewed_at' => null,
            'viewed_count' => 0,
            'last_viewed_at' => null,
            'accepted_at' => null,
            'declined_at' => null,
        ]);
    }

    public function accepted(): static
    {
        return $this->state([
            'status' => 'accepted',
            'sent_at' => now()->subDays(7),
            'viewed_at' => now()->subDays(5),
            'viewed_count' => 2,
            'last_viewed_at' => now()->subDays(5),
            'accepted_at' => now()->subDays(4),
            'patient_signature' => 'data:image/png;base64,iVBORw0KGgo=',
            'patient_signed_at' => now()->subDays(4),
            'patient_ip_at_signing' => '85.75.192.44',
            'patient_user_agent_at_signing' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X)',
        ]);
    }

    public function template(): static
    {
        return $this->state([
            'is_template' => true,
            'template_name' => $this->faker->randomElement([
                'Full Mouth Rehab Template',
                'Single Implant Template',
                'Smile Makeover Template',
            ]),
            'status' => 'draft',
        ]);
    }
}
