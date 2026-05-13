<?php

namespace Database\Factories;

use App\Models\TreatmentCategory;
use App\Models\TreatmentTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentTemplate>
 */
class TreatmentTemplateFactory extends Factory
{
    public static array $treatments = [
        [
            'code' => 'IMP-001',
            'name' => 'Single Dental Implant',
            'description_short' => 'A single titanium implant fixture with a porcelain crown restoration.',
            'description_long' => 'A dental implant is a titanium post surgically placed into the jawbone to act as an artificial tooth root. Once osseointegration is complete (typically 3 to 6 months), a custom porcelain crown is attached. The result is a natural-looking, fully functional tooth replacement.',
            'standard_duration_days' => 120,
            'requires_imaging' => true,
        ],
        [
            'code' => 'IMP-002',
            'name' => 'All-on-4 Dental Implants',
            'description_short' => 'Full arch fixed restoration supported by four dental implants.',
            'description_long' => 'The All-on-4 technique replaces an entire arch of missing teeth with a fixed prosthesis anchored to just four strategically placed implants. Same-day provisional teeth are often possible. The final prosthesis is fitted once healing is confirmed.',
            'standard_duration_days' => 180,
            'requires_imaging' => true,
        ],
        [
            'code' => 'IMP-003',
            'name' => 'All-on-6 Dental Implants',
            'description_short' => 'Full arch fixed restoration supported by six dental implants for added stability.',
            'description_long' => 'The All-on-6 protocol provides additional implant support compared to All-on-4, making it suitable for patients with moderate bone density. Six implants per arch provide a more evenly distributed load and greater long-term stability.',
            'standard_duration_days' => 180,
            'requires_imaging' => true,
        ],
        [
            'code' => 'CRN-001',
            'name' => 'Zirconia Crown',
            'description_short' => 'Full-contour monolithic zirconia crown for outstanding strength and aesthetics.',
            'description_long' => 'Zirconia crowns are milled from a solid block of zirconium dioxide, offering exceptional durability and a natural translucent appearance. They require no metal substructure, eliminating the risk of grey margins at the gumline.',
            'standard_duration_days' => 7,
            'requires_imaging' => false,
        ],
        [
            'code' => 'CRN-002',
            'name' => 'E.max Porcelain Crown',
            'description_short' => 'Lithium disilicate all-ceramic crown for highly aesthetic front-tooth restorations.',
            'description_long' => 'IPS e.max crowns are fabricated from lithium disilicate glass-ceramic, providing excellent translucency that closely mimics natural enamel. Ideal for anterior teeth where aesthetics is the primary concern.',
            'standard_duration_days' => 7,
            'requires_imaging' => false,
        ],
        [
            'code' => 'VEN-001',
            'name' => 'Porcelain Veneer',
            'description_short' => 'Ultra-thin porcelain shell bonded to the front surface of a tooth.',
            'description_long' => 'Porcelain veneers are custom-made thin shells that cover the visible surface of teeth to improve colour, shape, and alignment. Minimal tooth preparation is required. They are highly stain-resistant and offer a long-lasting aesthetic result.',
            'standard_duration_days' => 10,
            'requires_imaging' => false,
        ],
        [
            'code' => 'VEN-002',
            'name' => 'Composite Veneer',
            'description_short' => 'Direct composite resin veneer applied and sculpted chair-side.',
            'description_long' => 'Composite veneers are applied directly to the tooth surface using composite resin material, shaped and polished in a single appointment. They offer a reversible, cost-effective option for smile improvements.',
            'standard_duration_days' => 1,
            'requires_imaging' => false,
        ],
        [
            'code' => 'WHT-001',
            'name' => 'In-clinic Teeth Whitening',
            'description_short' => 'Professional power whitening treatment in a single clinic visit.',
            'description_long' => 'Using a high-concentration peroxide gel activated by LED light, in-clinic whitening can lighten teeth by several shades in approximately one hour. Custom trays are provided for maintenance top-ups at home.',
            'standard_duration_days' => 1,
            'requires_imaging' => false,
        ],
        [
            'code' => 'ORT-001',
            'name' => 'Clear Aligner Treatment',
            'description_short' => 'Removable clear aligners for discreet teeth straightening.',
            'description_long' => 'A series of custom-made, removable clear aligners gradually move teeth into the desired position. Treatment is planned using 3D digital scanning. Duration ranges from 6 to 24 months depending on case complexity.',
            'standard_duration_days' => 365,
            'requires_imaging' => true,
        ],
        [
            'code' => 'END-001',
            'name' => 'Root Canal Treatment',
            'description_short' => 'Endodontic treatment to remove infected pulp and save the natural tooth.',
            'description_long' => 'Root canal treatment involves removing the infected or inflamed pulp tissue, cleaning and shaping the root canals, and sealing them with a biocompatible material. A crown is typically recommended to protect the restored tooth.',
            'standard_duration_days' => 14,
            'requires_imaging' => true,
        ],
        [
            'code' => 'SRG-001',
            'name' => 'Wisdom Tooth Extraction',
            'description_short' => 'Surgical removal of impacted or problematic wisdom teeth.',
            'description_long' => 'Wisdom tooth extraction may be performed under local anaesthetic or IV sedation. The procedure involves sectioning the tooth if impacted and removing it in pieces to minimise trauma to surrounding bone and tissue.',
            'standard_duration_days' => 7,
            'requires_imaging' => true,
        ],
        [
            'code' => 'SRG-002',
            'name' => 'Simple Tooth Extraction',
            'description_short' => 'Straightforward removal of a non-restorable tooth under local anaesthetic.',
            'description_long' => 'A simple extraction is performed when a tooth is fully erupted and can be removed in one piece. The procedure is carried out under local anaesthetic and takes approximately 15 to 30 minutes.',
            'standard_duration_days' => 3,
            'requires_imaging' => false,
        ],
        [
            'code' => 'PRD-001',
            'name' => 'Full Upper Denture',
            'description_short' => 'Custom-made removable complete upper denture.',
            'description_long' => 'A full upper denture replaces all missing upper teeth and is held in place by suction against the palate. It is custom-fabricated from acrylic resin over multiple appointments, including shade and try-in stages.',
            'standard_duration_days' => 21,
            'requires_imaging' => false,
        ],
        [
            'code' => 'GEN-001',
            'name' => 'Composite Filling',
            'description_short' => 'Tooth-coloured composite resin filling to restore a decayed tooth.',
            'description_long' => 'A composite resin filling is used to restore a tooth affected by decay or minor fracture. The decay is removed, the cavity prepared, and the composite material is placed and cured with a UV light, then polished to a natural finish.',
            'standard_duration_days' => 1,
            'requires_imaging' => false,
        ],
    ];

    public function definition(): array
    {
        $treatment = $this->faker->randomElement(self::$treatments);

        return [
            'clinic_id' => null,
            'category_id' => TreatmentCategory::factory(),
            'code' => $treatment['code'],
            'name' => $treatment['name'],
            'description_short' => $treatment['description_short'],
            'description_long' => $treatment['description_long'],
            'procedure_steps' => json_encode($this->sampleProcedureSteps($treatment['code'])),
            'recovery_info' => $this->sampleRecoveryInfo($treatment['code']),
            'materials_options' => null,
            'guarantee_text' => $this->faker->optional(0.7)->randomElement([
                'This implant carries a lifetime guarantee against implant failure subject to annual review appointments.',
                'Crown and bridge work carries a 5-year guarantee against material failure.',
                'Porcelain veneers carry a 5-year aesthetic guarantee.',
                'Whitening results guaranteed for a minimum of 12 months with the use of the provided maintenance trays.',
            ]),
            'legal_disclaimer' => 'Results may vary between patients. All treatment is subject to a full clinical assessment. This treatment plan does not constitute a guarantee of outcome.',
            'standard_duration_days' => $treatment['standard_duration_days'],
            'requires_imaging' => $treatment['requires_imaging'],
            'default_animation_clip_ids' => null,
            'default_before_after_ids' => null,
            'is_active' => true,
            'usage_count' => $this->faker->numberBetween(0, 250),
        ];
    }

    private function sampleProcedureSteps(string $code): array
    {
        $steps = [
            'IMP-001' => [
                'Initial consultation and panoramic X-ray',
                'Implant fixture placement under local anaesthetic',
                'Healing period (osseointegration): 3 to 6 months',
                'Abutment placement and crown impression',
                'Crown fitting and occlusal adjustment',
            ],
            'IMP-002' => [
                'Comprehensive assessment including CBCT scan',
                'Extraction of remaining teeth (if required)',
                'Placement of four implant fixtures',
                'Attachment of provisional fixed prosthesis on the day',
                'Healing period: 4 to 6 months',
                'Fabrication and fitting of final full-arch prosthesis',
            ],
            'CRN-001' => [
                'Tooth preparation and shade selection',
                'Digital or physical impression',
                'Fitting of temporary crown',
                'Crown fabrication by dental laboratory (5 to 7 days)',
                'Permanent crown cementation and occlusal check',
            ],
            'VEN-001' => [
                'Smile design consultation and mock-up',
                'Minimal tooth preparation',
                'Impression and shade selection',
                'Temporary veneers fitted',
                'Porcelain veneers bonded with adhesive composite',
            ],
        ];

        return $steps[$code] ?? [
            'Initial assessment and treatment planning',
            'Procedure carried out under local anaesthetic',
            'Post-treatment review and care instructions provided',
        ];
    }

    private function sampleRecoveryInfo(string $code): ?string
    {
        $info = [
            'IMP-001' => 'Mild swelling and discomfort for 3 to 5 days after placement. Avoid hard foods for 6 weeks. Maintain excellent oral hygiene around the implant site.',
            'IMP-002' => 'Swelling and bruising are common for the first week. A soft diet is required for 8 to 12 weeks. Regular review appointments are essential during osseointegration.',
            'CRN-001' => 'Mild sensitivity to temperature is normal for a few days after cementation. Avoid very hard foods for the first 48 hours.',
            'END-001' => 'Some tenderness on biting for up to two weeks following treatment. Over-the-counter analgesics are usually sufficient for pain relief.',
            'SRG-001' => 'Swelling peaks at 48 to 72 hours. Keep the socket clean with warm salt water rinses from 24 hours post-extraction. Avoid smoking.',
        ];

        return $info[$code] ?? 'Follow post-treatment care instructions as provided by your clinician. Contact the clinic if you experience any unusual symptoms.';
    }
}
