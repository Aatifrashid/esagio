<?php

namespace Database\Factories;

use App\Models\TreatmentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<TreatmentCategory>
 */
class TreatmentCategoryFactory extends Factory
{
    /**
     * Canonical dental treatment categories used as seed data.
     */
    public const CATEGORIES = [
        [
            'name' => 'Implants',
            'slug' => 'implants',
            'description' => 'Titanium implant fixtures and full implant-supported restorations.',
            'icon' => 'tooth-implant',
            'sort_order' => 1,
        ],
        [
            'name' => 'Crowns and Bridges',
            'slug' => 'crowns-and-bridges',
            'description' => 'Porcelain, zirconia, and PFM crowns and fixed bridge restorations.',
            'icon' => 'crown',
            'sort_order' => 2,
        ],
        [
            'name' => 'Veneers',
            'slug' => 'veneers',
            'description' => 'Porcelain and composite veneers for smile makeovers.',
            'icon' => 'veneer',
            'sort_order' => 3,
        ],
        [
            'name' => 'Teeth Whitening',
            'slug' => 'teeth-whitening',
            'description' => 'In-clinic and home whitening treatments.',
            'icon' => 'whitening',
            'sort_order' => 4,
        ],
        [
            'name' => 'Orthodontics',
            'slug' => 'orthodontics',
            'description' => 'Braces, clear aligners, and retainers.',
            'icon' => 'braces',
            'sort_order' => 5,
        ],
        [
            'name' => 'Periodontics',
            'slug' => 'periodontics',
            'description' => 'Gum disease treatment, deep cleaning, and periodontal surgery.',
            'icon' => 'gum',
            'sort_order' => 6,
        ],
        [
            'name' => 'Oral Surgery',
            'slug' => 'oral-surgery',
            'description' => 'Tooth extractions, wisdom tooth removal, and minor oral surgery.',
            'icon' => 'surgery',
            'sort_order' => 7,
        ],
        [
            'name' => 'Prosthodontics',
            'slug' => 'prosthodontics',
            'description' => 'Full and partial dentures and complex prosthetic restorations.',
            'icon' => 'denture',
            'sort_order' => 8,
        ],
        [
            'name' => 'Endodontics',
            'slug' => 'endodontics',
            'description' => 'Root canal treatment and re-treatment.',
            'icon' => 'root-canal',
            'sort_order' => 9,
        ],
        [
            'name' => 'General Dentistry',
            'slug' => 'general-dentistry',
            'description' => 'Routine check-ups, fillings, scale and polish.',
            'icon' => 'checkup',
            'sort_order' => 10,
        ],
    ];

    public function definition(): array
    {
        $category = $this->faker->randomElement(self::CATEGORIES);

        return [
            'clinic_id' => null,
            'name' => $category['name'],
            'slug' => $category['slug'].'-'.Str::random(4),
            'parent_id' => null,
            'description' => $category['description'],
            'icon' => $category['icon'],
            'sort_order' => $category['sort_order'],
        ];
    }
}
