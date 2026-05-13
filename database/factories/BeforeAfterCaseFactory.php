<?php

namespace Database\Factories;

use App\Models\BeforeAfterCase;
use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BeforeAfterCase>
 */
class BeforeAfterCaseFactory extends Factory
{
    private static array $cases = [
        [
            'title' => 'Full Mouth Rehabilitation with Implants',
            'description' => 'Patient presented with multiple missing and failing teeth due to advanced periodontal disease. A full mouth rehabilitation was completed using eight dental implants supporting fixed zirconia bridgework on both arches.',
            'patient_age_range' => '45-55',
            'patient_gender' => 'male',
            'treatment_duration_days' => 180,
            'treatment_value' => 22000.00,
            'tags' => 'implants,full-rehab,zirconia',
        ],
        [
            'title' => 'Smile Makeover with Porcelain Veneers',
            'description' => 'Eight porcelain veneers placed on the upper anterior teeth to correct crowding, discolouration, and minor alignment issues. Combined with in-clinic whitening of the lower teeth.',
            'patient_age_range' => '30-40',
            'patient_gender' => 'female',
            'treatment_duration_days' => 14,
            'treatment_value' => 6200.00,
            'tags' => 'veneers,cosmetic,whitening',
        ],
        [
            'title' => 'All-on-4 Upper Arch Restoration',
            'description' => 'Patient had a failing upper partial denture and several root-treated teeth with a poor prognosis. All remaining upper teeth were extracted and four implants placed with a same-day provisional prosthesis.',
            'patient_age_range' => '55-65',
            'patient_gender' => 'male',
            'treatment_duration_days' => 150,
            'treatment_value' => 9500.00,
            'tags' => 'implants,all-on-4',
        ],
        [
            'title' => 'Upper Anterior Crown Replacements',
            'description' => 'Six old porcelain-fused-to-metal crowns replaced with e.max lithium disilicate crowns to eliminate dark margins and improve the overall smile aesthetics.',
            'patient_age_range' => '40-50',
            'patient_gender' => 'female',
            'treatment_duration_days' => 10,
            'treatment_value' => 5700.00,
            'tags' => 'crowns,cosmetic,emax',
        ],
        [
            'title' => 'Single Implant to Replace Missing Molar',
            'description' => 'Patient lost a lower right first molar. A single Straumann implant was placed with a zirconia crown after a 4-month healing period. Bone grafting was required at the time of placement.',
            'patient_age_range' => '35-45',
            'patient_gender' => 'male',
            'treatment_duration_days' => 120,
            'treatment_value' => 3200.00,
            'tags' => 'implants,bone-graft',
        ],
        [
            'title' => 'Clear Aligner Orthodontic Treatment',
            'description' => 'Moderate crowding on both arches corrected with 28 sets of clear aligners over 14 months. Finishing veneers applied to two peg laterals.',
            'patient_age_range' => '25-35',
            'patient_gender' => 'female',
            'treatment_duration_days' => 420,
            'treatment_value' => 4800.00,
            'tags' => 'orthodontics,aligners,veneers',
        ],
        [
            'title' => 'Composite Smile Makeover',
            'description' => 'Ten direct composite veneers placed chair-side in a single six-hour appointment to transform a worn and discoloured smile. No tooth preparation required.',
            'patient_age_range' => '28-38',
            'patient_gender' => 'female',
            'treatment_duration_days' => 1,
            'treatment_value' => 2500.00,
            'tags' => 'composite,veneers,cosmetic',
        ],
        [
            'title' => 'All-on-6 Full Arch Implant Rehabilitation',
            'description' => 'Both arches restored with the All-on-6 technique using Nobel Biocare implants. The patient flew from the UK for staged treatment over two visits. Final zirconia prostheses fitted on the second visit.',
            'patient_age_range' => '60-70',
            'patient_gender' => 'male',
            'treatment_duration_days' => 180,
            'treatment_value' => 20000.00,
            'tags' => 'implants,all-on-6,full-rehab',
        ],
    ];

    public function definition(): array
    {
        $case = $this->faker->randomElement(self::$cases);

        return [
            'clinic_id' => Clinic::factory(),
            'title' => $case['title'],
            'description' => $case['description'],
            'patient_age_range' => $case['patient_age_range'],
            'patient_gender' => $case['patient_gender'],
            'treatment_duration_days' => $case['treatment_duration_days'],
            'before_image_path' => null,
            'after_image_path' => null,
            'additional_images' => null,
            'is_featured' => $this->faker->boolean(25),
            'consent_obtained' => $this->faker->boolean(80),
            'tags' => $case['tags'],
            'treatment_value' => $case['treatment_value'],
        ];
    }

    public function featured(): static
    {
        return $this->state([
            'is_featured' => true,
            'consent_obtained' => true,
        ]);
    }

    public function implant(): static
    {
        return $this->state([
            'title' => 'Full Mouth Rehabilitation with Implants',
            'patient_age_range' => '45-55',
            'tags' => 'implants,full-rehab,zirconia',
            'treatment_value' => 22000.00,
        ]);
    }
}
