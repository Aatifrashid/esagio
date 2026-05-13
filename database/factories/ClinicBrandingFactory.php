<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\ClinicBranding;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicBranding>
 */
class ClinicBrandingFactory extends Factory
{
    private static array $brandPresets = [
        [
            'primary_colour' => '#0A2540',
            'accent_colour' => '#E8663D',
            'heading_font' => 'Fraunces',
            'body_font' => 'Inter',
        ],
        [
            'primary_colour' => '#1B2B4B',
            'accent_colour' => '#C9A96E',
            'heading_font' => 'Playfair Display',
            'body_font' => 'Lato',
        ],
        [
            'primary_colour' => '#1A1A2E',
            'accent_colour' => '#16213E',
            'heading_font' => 'Cormorant Garamond',
            'body_font' => 'Montserrat',
        ],
        [
            'primary_colour' => '#006D77',
            'accent_colour' => '#E29578',
            'heading_font' => 'Libre Baskerville',
            'body_font' => 'Open Sans',
        ],
        [
            'primary_colour' => '#2C3E50',
            'accent_colour' => '#E74C3C',
            'heading_font' => 'EB Garamond',
            'body_font' => 'Source Sans Pro',
        ],
    ];

    public function definition(): array
    {
        $preset = $this->faker->randomElement(self::$brandPresets);

        return [
            'clinic_id' => Clinic::factory(),
            'cover_page_image' => null,
            'logo_dark' => null,
            'logo_light' => null,
            'primary_colour' => $preset['primary_colour'],
            'accent_colour' => $preset['accent_colour'],
            'heading_font' => $preset['heading_font'],
            'body_font' => $preset['body_font'],
            'footer_text' => $this->faker->optional(0.6)->randomElement([
                'Regulated by the General Dental Council (GDC). Registration number available on request.',
                'Member of the British Dental Association. All clinicians are GDC registered.',
                'CQC registered dental practice. Committed to safe, effective, and person-centred care.',
            ]),
            'social_links' => json_encode([
                'instagram' => 'https://instagram.com/'.$this->faker->slug(2),
                'facebook' => 'https://facebook.com/'.$this->faker->slug(2),
                'website' => 'https://'.$this->faker->domainName(),
            ]),
            'accreditations' => json_encode([
                'GDC Registered',
                'BDA Member',
                'Invisalign Preferred Provider',
                'Nobel Biocare Certified',
            ]),
            'certifications_images' => null,
        ];
    }

    public function default(): static
    {
        return $this->state([
            'primary_colour' => '#0A2540',
            'accent_colour' => '#E8663D',
            'heading_font' => 'Fraunces',
            'body_font' => 'Inter',
        ]);
    }
}
