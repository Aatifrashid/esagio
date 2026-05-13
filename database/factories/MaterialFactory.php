<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Material>
 */
class MaterialFactory extends Factory
{
    private static array $materials = [
        [
            'name' => 'Nobel Biocare NobelParallel CC Implant',
            'category' => 'implant',
            'brand' => 'Nobel Biocare',
            'description' => 'Bone-level parallel-walled implant with a self-cutting apex. Suitable for a wide range of indications including immediate loading protocols.',
            'country_of_origin' => 'Switzerland',
            'warranty_years' => null,
            'is_premium' => true,
        ],
        [
            'name' => 'Straumann Bone Level Tapered Implant',
            'category' => 'implant',
            'brand' => 'Straumann',
            'description' => 'Tapered body design with SLActive surface treatment for rapid osseointegration. Bone-level positioning for optimal soft tissue management.',
            'country_of_origin' => 'Switzerland',
            'warranty_years' => null,
            'is_premium' => true,
        ],
        [
            'name' => 'Osstem TSIII Implant',
            'category' => 'implant',
            'brand' => 'Osstem',
            'description' => 'Korean-manufactured implant with SA surface treatment. High strength titanium alloy (Ti-6Al-4V). Widely used in Turkey and Eastern Europe.',
            'country_of_origin' => 'South Korea',
            'warranty_years' => null,
            'is_premium' => false,
        ],
        [
            'name' => 'MegaGen AnyRidge Implant',
            'category' => 'implant',
            'brand' => 'MegaGen',
            'description' => 'Multi-thread design with advanced surface treatment for immediate loading in poor bone quality. Popular for full-arch rehabilitation.',
            'country_of_origin' => 'South Korea',
            'warranty_years' => null,
            'is_premium' => false,
        ],
        [
            'name' => 'IPS e.max CAD Block',
            'category' => 'crown',
            'brand' => 'Ivoclar Vivadent',
            'description' => 'Lithium disilicate glass-ceramic CAD/CAM block for milling crowns, inlays, and veneers. Outstanding translucency and flexural strength of 370 MPa.',
            'country_of_origin' => 'Liechtenstein',
            'warranty_years' => 5,
            'is_premium' => true,
        ],
        [
            'name' => 'Katana Zirconia STML Block',
            'category' => 'crown',
            'brand' => 'Kuraray Noritake',
            'description' => 'Super Translucent Multi-Layer zirconia with a natural colour gradient. Flexural strength 750 MPa. Ideal for anterior full-contour restorations.',
            'country_of_origin' => 'Japan',
            'warranty_years' => 5,
            'is_premium' => true,
        ],
        [
            'name' => 'VITA Mark II Feldspathic Porcelain Block',
            'category' => 'crown',
            'brand' => 'VITA Zahnfabrik',
            'description' => 'Fine-grain feldspathic ceramic with a natural matte appearance. Used for CAD/CAM milling of anterior crowns and inlays.',
            'country_of_origin' => 'Germany',
            'warranty_years' => 5,
            'is_premium' => false,
        ],
        [
            'name' => 'Empress CAD Veneer Block',
            'category' => 'veneer',
            'brand' => 'Ivoclar Vivadent',
            'description' => 'Leucite-reinforced glass-ceramic block specifically designed for thin CAD/CAM veneers. Flexural strength 160 MPa.',
            'country_of_origin' => 'Liechtenstein',
            'warranty_years' => 5,
            'is_premium' => true,
        ],
        [
            'name' => 'Filtek Supreme Ultra Composite',
            'category' => 'composite',
            'brand' => '3M ESPE',
            'description' => 'Nano-hybrid composite resin for direct anterior and posterior restorations. Available in a wide range of shades to match natural dentition.',
            'country_of_origin' => 'United States',
            'warranty_years' => null,
            'is_premium' => true,
        ],
        [
            'name' => 'GC Initial Porcelain Layering Ceramic',
            'category' => 'porcelain',
            'brand' => 'GC Corporation',
            'description' => 'Fluorapatite-based layering ceramic for hand-built porcelain restorations. Excellent biocompatibility and outstanding optical properties.',
            'country_of_origin' => 'Japan',
            'warranty_years' => null,
            'is_premium' => true,
        ],
        [
            'name' => 'Opalescence PF 35% Whitening Gel',
            'category' => 'whitening',
            'brand' => 'Ultradent',
            'description' => 'Professional-strength take-home whitening gel in custom trays. Potassium nitrate and fluoride for reduced sensitivity.',
            'country_of_origin' => 'United States',
            'warranty_years' => null,
            'is_premium' => false,
        ],
        [
            'name' => 'ZOOM! Advanced Power Whitening Gel',
            'category' => 'whitening',
            'brand' => 'Philips',
            'description' => 'In-clinic LED-activated hydrogen peroxide whitening gel. Up to 8 shades lighter in a single 45-minute session.',
            'country_of_origin' => 'United States',
            'warranty_years' => null,
            'is_premium' => true,
        ],
        [
            'name' => 'BioHorizons Tapered Internal Implant',
            'category' => 'implant',
            'brand' => 'BioHorizons',
            'description' => 'Laser-Lok microstructured collar implant for bone and soft tissue retention. USA-manufactured with a proven long-term clinical record.',
            'country_of_origin' => 'United States',
            'warranty_years' => null,
            'is_premium' => true,
        ],
        [
            'name' => 'Nobel Biocare NobelProcera Zirconia Bridge',
            'category' => 'bridge',
            'brand' => 'Nobel Biocare',
            'description' => 'Milled full-contour zirconia bridge framework with exceptional fit accuracy. Can span up to 14 units.',
            'country_of_origin' => 'Sweden',
            'warranty_years' => 5,
            'is_premium' => true,
        ],
    ];

    public function definition(): array
    {
        $material = $this->faker->randomElement(self::$materials);

        return [
            'clinic_id' => null,
            'name' => $material['name'],
            'category' => $material['category'],
            'brand' => $material['brand'],
            'description' => $material['description'],
            'country_of_origin' => $material['country_of_origin'],
            'warranty_years' => $material['warranty_years'],
            'is_premium' => $material['is_premium'],
            'image_path' => null,
        ];
    }

    public function implant(): static
    {
        $implants = array_filter(self::$materials, fn ($m) => $m['category'] === 'implant');
        $implant = $this->faker->randomElement(array_values($implants));

        return $this->state([
            'name' => $implant['name'],
            'category' => 'implant',
            'brand' => $implant['brand'],
            'country_of_origin' => $implant['country_of_origin'],
            'is_premium' => $implant['is_premium'],
        ]);
    }

    public function premium(): static
    {
        return $this->state(['is_premium' => true]);
    }
}
