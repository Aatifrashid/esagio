<?php

namespace Database\Factories;

use App\Models\ToothChartCondition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ToothChartCondition>
 */
class ToothChartConditionFactory extends Factory
{
    /**
     * All 20 canonical conditions for seeding.
     */
    public const CONDITIONS = [
        [
            'code' => 'missing',
            'name' => 'Missing Tooth',
            'colour' => '#64748B',
            'icon' => 'missing',
            'description' => 'Tooth is absent from the mouth. May have been extracted or never erupted.',
            'sort_order' => 1,
        ],
        [
            'code' => 'decayed',
            'name' => 'Decayed (Caries)',
            'colour' => '#DC2626',
            'icon' => 'caries',
            'description' => 'Active dental caries (decay) present on one or more surfaces of the tooth.',
            'sort_order' => 2,
        ],
        [
            'code' => 'to_extract',
            'name' => 'To Be Extracted',
            'colour' => '#EF4444',
            'icon' => 'extraction',
            'description' => 'Tooth is planned for extraction due to non-restorability or strategic reasons.',
            'sort_order' => 3,
        ],
        [
            'code' => 'root_canal_done',
            'name' => 'Root Canal Treated',
            'colour' => '#7C3AED',
            'icon' => 'root-canal-done',
            'description' => 'Root canal treatment has been completed on this tooth.',
            'sort_order' => 4,
        ],
        [
            'code' => 'root_canal_needed',
            'name' => 'Root Canal Needed',
            'colour' => '#A855F7',
            'icon' => 'root-canal-needed',
            'description' => 'Root canal treatment is indicated but has not yet been performed.',
            'sort_order' => 5,
        ],
        [
            'code' => 'crowned',
            'name' => 'Crown Present',
            'colour' => '#F59E0B',
            'icon' => 'crown',
            'description' => 'An existing crown (metal, porcelain, or zirconia) is present on this tooth.',
            'sort_order' => 6,
        ],
        [
            'code' => 'implant_existing',
            'name' => 'Implant (Existing)',
            'colour' => '#0EA5E9',
            'icon' => 'implant-existing',
            'description' => 'A dental implant has already been placed at this position.',
            'sort_order' => 7,
        ],
        [
            'code' => 'implant_planned',
            'name' => 'Implant (Planned)',
            'colour' => '#38BDF8',
            'icon' => 'implant-planned',
            'description' => 'A dental implant is planned at this position as part of the treatment plan.',
            'sort_order' => 8,
        ],
        [
            'code' => 'veneer_existing',
            'name' => 'Veneer (Existing)',
            'colour' => '#F472B6',
            'icon' => 'veneer-existing',
            'description' => 'An existing porcelain or composite veneer is present on the labial surface.',
            'sort_order' => 9,
        ],
        [
            'code' => 'veneer_planned',
            'name' => 'Veneer (Planned)',
            'colour' => '#FB7185',
            'icon' => 'veneer-planned',
            'description' => 'A veneer is planned for this tooth as part of the smile design.',
            'sort_order' => 10,
        ],
        [
            'code' => 'filling',
            'name' => 'Filling Present',
            'colour' => '#6B7280',
            'icon' => 'filling',
            'description' => 'An existing restoration (amalgam, composite, or GIC) is present on this tooth.',
            'sort_order' => 11,
        ],
        [
            'code' => 'fractured',
            'name' => 'Fractured Tooth',
            'colour' => '#92400E',
            'icon' => 'fracture',
            'description' => 'A crack or fracture is present. May or may not be symptomatic.',
            'sort_order' => 12,
        ],
        [
            'code' => 'sensitive',
            'name' => 'Sensitive',
            'colour' => '#FBBF24',
            'icon' => 'sensitive',
            'description' => 'Patient reports dentine hypersensitivity at this tooth.',
            'sort_order' => 13,
        ],
        [
            'code' => 'bridge_abutment',
            'name' => 'Bridge Abutment',
            'colour' => '#D97706',
            'icon' => 'bridge-abutment',
            'description' => 'This tooth is a supporting abutment for a fixed dental bridge.',
            'sort_order' => 14,
        ],
        [
            'code' => 'bridge_pontic',
            'name' => 'Bridge Pontic',
            'colour' => '#B45309',
            'icon' => 'bridge-pontic',
            'description' => 'This is a pontic (dummy tooth) in a fixed dental bridge.',
            'sort_order' => 15,
        ],
        [
            'code' => 'denture',
            'name' => 'Denture',
            'colour' => '#78716C',
            'icon' => 'denture',
            'description' => 'A removable denture (partial or full) is in situ covering this region.',
            'sort_order' => 16,
        ],
        [
            'code' => 'periodontal',
            'name' => 'Periodontal Disease',
            'colour' => '#16A34A',
            'icon' => 'perio',
            'description' => 'Active periodontitis or significant bone loss is recorded at this site.',
            'sort_order' => 17,
        ],
        [
            'code' => 'abscess',
            'name' => 'Abscess / Infection',
            'colour' => '#B91C1C',
            'icon' => 'abscess',
            'description' => 'A periapical or periodontal abscess is present at this tooth.',
            'sort_order' => 18,
        ],
        [
            'code' => 'impacted',
            'name' => 'Impacted Tooth',
            'colour' => '#475569',
            'icon' => 'impacted',
            'description' => 'The tooth is impacted and unable to fully erupt into its normal position.',
            'sort_order' => 19,
        ],
        [
            'code' => 'erupting',
            'name' => 'Erupting Tooth',
            'colour' => '#22C55E',
            'icon' => 'erupting',
            'description' => 'The tooth is in the process of erupting and not yet fully in occlusion.',
            'sort_order' => 20,
        ],
    ];

    public function definition(): array
    {
        $condition = $this->faker->randomElement(self::CONDITIONS);

        return [
            'code' => $condition['code'].'_'.$this->faker->unique()->randomNumber(4),
            'name' => $condition['name'],
            'colour' => $condition['colour'],
            'icon' => $condition['icon'],
            'description' => $condition['description'],
            'sort_order' => $condition['sort_order'],
        ];
    }
}
