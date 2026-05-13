<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    public function definition(): array
    {
        $ukFirstNames = [
            'James', 'Oliver', 'Harry', 'George', 'Noah',
            'Emily', 'Olivia', 'Isla', 'Sophie', 'Amelia',
            'Thomas', 'William', 'Jack', 'Charlie', 'Alfie',
            'Jessica', 'Lily', 'Grace', 'Poppy', 'Ruby',
        ];

        $ukLastNames = [
            'Smith', 'Jones', 'Williams', 'Taylor', 'Brown',
            'Davies', 'Evans', 'Wilson', 'Thomas', 'Roberts',
            'Johnson', 'Lewis', 'Walker', 'Robinson', 'White',
            'Thompson', 'Martin', 'Clarke', 'Hall', 'Wood',
        ];

        $turkishFirstNames = [
            'Ahmet', 'Mehmet', 'Mustafa', 'Emre', 'Burak',
            'Ayse', 'Fatma', 'Zeynep', 'Esra', 'Elif',
            'Can', 'Cem', 'Baris', 'Selin', 'Merve',
            'Yusuf', 'Ibrahim', 'Ozlem', 'Derya', 'Hakan',
        ];

        $turkishLastNames = [
            'Yilmaz', 'Kaya', 'Demir', 'Sahin', 'Celik',
            'Arslan', 'Ozturk', 'Aydin', 'Ozdemir', 'Er',
            'Kurt', 'Kaplan', 'Cakmak', 'Koc', 'Bulut',
            'Unal', 'Yildiz', 'Polat', 'Aksoy', 'Guzel',
        ];

        $isTurkish = $this->faker->boolean(40);

        $firstName = $isTurkish
            ? $this->faker->randomElement($turkishFirstNames)
            : $this->faker->randomElement($ukFirstNames);

        $lastName = $isTurkish
            ? $this->faker->randomElement($turkishLastNames)
            : $this->faker->randomElement($ukLastNames);

        $allTags = ['implants', 'cosmetic', 'vip', 'whitening', 'orthodontics', 'full-rehab', 'recall', 'finance-plan'];

        $status = $this->faker->randomElement([
            'lead', 'consulting', 'plan_sent', 'plan_accepted',
            'treatment_scheduled', 'in_treatment', 'completed', 'cancelled', 'lost',
        ]);

        return [
            'clinic_id' => Clinic::factory(),
            'reference_code' => 'PAT-'.strtoupper(Str::random(6)),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => strtolower($firstName.'.'.$lastName.$this->faker->numberBetween(1, 99)).'@'.$this->faker->randomElement(['gmail.com', 'hotmail.co.uk', 'yahoo.co.uk', 'outlook.com']),
            'phone' => $this->faker->randomElement(['+44', '+90']).$this->faker->numerify(' 7## ### ####'),
            'whatsapp_number' => $this->faker->optional(0.7)->numerify('+447#########'),
            'country_of_residence' => $isTurkish ? 'TR' : 'GB',
            'city' => $isTurkish
                ? $this->faker->randomElement(['Istanbul', 'Ankara', 'Izmir', 'Antalya', 'Bursa'])
                : $this->faker->randomElement(['London', 'Manchester', 'Birmingham', 'Leeds', 'Bristol', 'Edinburgh']),
            'date_of_birth' => $this->faker->dateTimeBetween('-70 years', '-20 years')->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'preferred_language' => $isTurkish ? $this->faker->randomElement(['en', 'tr']) : 'en',
            'occupation' => $this->faker->randomElement([
                'Accountant', 'Teacher', 'Engineer', 'Nurse', 'Manager',
                'Business Owner', 'Retired', 'Sales Executive', 'Lawyer', 'Doctor',
            ]),
            'medical_history' => $this->faker->optional(0.4)->passthrough(
                json_encode([
                    'diabetes' => $this->faker->boolean(10),
                    'hypertension' => $this->faker->boolean(15),
                    'heart_disease' => $this->faker->boolean(8),
                    'smoker' => $this->faker->boolean(20),
                    'blood_thinner' => $this->faker->boolean(12),
                ])
            ),
            'allergies' => $this->faker->optional(0.2)->randomElement([
                'Penicillin', 'Aspirin', 'Latex', 'Lidocaine', 'Ibuprofen',
            ]),
            'current_medications' => $this->faker->optional(0.25)->randomElement([
                'Warfarin 5mg daily', 'Metformin 500mg twice daily',
                'Amlodipine 10mg daily', 'Atorvastatin 20mg at night',
            ]),
            'dental_history_notes' => $this->faker->optional(0.5)->randomElement([
                'Previous orthodontic treatment as a teenager.',
                'Multiple extractions in the past. Denture on lower right.',
                'Periodontal treatment completed two years ago.',
                'Existing bridge on upper left from 2019.',
                'Severe wear due to bruxism. Wears a night guard.',
                'Full upper denture since 2015.',
            ]),
            'source' => $this->faker->randomElement([
                'Google Ads', 'Instagram', 'referral', 'walk-in', 'website', 'Facebook',
            ]),
            'referred_by_patient_id' => null,
            'assigned_to' => null,
            'created_by' => null,
            'status' => $status,
            'notes' => $this->faker->optional(0.5)->randomElement([
                'Very interested in full mouth rehabilitation.',
                'Price sensitive -- offered finance options.',
                'Came via referral from existing patient. High trust.',
                'Wants treatment completed before summer holiday.',
                'Nervous patient. Will need extra reassurance.',
                'Enquired about implants after losing a molar last year.',
            ]),
            'tags' => $this->faker->optional(0.6)->passthrough(
                json_encode($this->faker->randomElements($allTags, $this->faker->numberBetween(1, 3)))
            ),
            'pipeline_stage_id' => null,
            'last_contacted_at' => $this->faker->optional(0.7)->dateTimeBetween('-60 days', 'now'),
            'next_action_at' => $this->faker->optional(0.5)->dateTimeBetween('now', '+30 days'),
            'next_action_description' => $this->faker->optional(0.5)->randomElement([
                'Send treatment plan PDF',
                'Follow-up call regarding implant quote',
                'Book consultation appointment',
                'Chase outstanding decision',
                'Send before/after case studies',
            ]),
            'deal_value' => $this->faker->randomFloat(2, 500, 15000),
        ];
    }

    public function lead(): static
    {
        return $this->state(['status' => 'lead']);
    }

    public function inTreatment(): static
    {
        return $this->state(['status' => 'in_treatment']);
    }

    public function completed(): static
    {
        return $this->state(['status' => 'completed']);
    }

    public function vip(): static
    {
        return $this->state([
            'tags' => json_encode(['vip', 'implants']),
            'deal_value' => $this->faker->randomFloat(2, 8000, 15000),
        ]);
    }
}
