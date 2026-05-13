<?php

namespace Database\Factories;

use App\Models\PriceList;
use App\Models\PriceListItem;
use App\Models\TreatmentTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PriceListItem>
 */
class PriceListItemFactory extends Factory
{
    /**
     * Canonical dental pricing in GBP.
     * Prices are per-unit unless noted.
     */
    private static array $pricing = [
        'Single Dental Implant' => ['price' => 2500.00, 'unit_label' => 'per_tooth',  'notes' => 'Includes implant fixture, abutment, and zirconia crown.'],
        'All-on-4 Dental Implants' => ['price' => 8500.00, 'unit_label' => 'per_arch',   'notes' => 'Per arch. Includes four implants, abutments, and provisional prosthesis.'],
        'All-on-6 Dental Implants' => ['price' => 10500.00, 'unit_label' => 'per_arch',   'notes' => 'Per arch. Includes six implants, abutments, and provisional prosthesis.'],
        'Zirconia Crown' => ['price' => 850.00,  'unit_label' => 'per_tooth',  'notes' => 'Includes laboratory fees and two fitting appointments.'],
        'E.max Porcelain Crown' => ['price' => 950.00,  'unit_label' => 'per_tooth',  'notes' => 'Lithium disilicate. Best suited for anterior teeth.'],
        'Porcelain Veneer' => ['price' => 650.00,  'unit_label' => 'per_tooth',  'notes' => 'Includes smile design consultation and temporary veneers.'],
        'Composite Veneer' => ['price' => 250.00,  'unit_label' => 'per_tooth',  'notes' => 'Chair-side composite resin. Single appointment.'],
        'In-clinic Teeth Whitening' => ['price' => 350.00,  'unit_label' => 'fixed',      'notes' => 'Includes home maintenance kit.'],
        'Clear Aligner Treatment' => ['price' => 3200.00, 'unit_label' => 'fixed',      'notes' => 'Full treatment including retainers. Price varies with complexity.'],
        'Root Canal Treatment' => ['price' => 550.00,  'unit_label' => 'per_tooth',  'notes' => 'Single-rooted tooth. Multi-rooted teeth charged separately.'],
        'Wisdom Tooth Extraction' => ['price' => 450.00,  'unit_label' => 'per_tooth',  'notes' => 'Surgical extraction under local anaesthetic.'],
        'Simple Tooth Extraction' => ['price' => 150.00,  'unit_label' => 'per_tooth',  'notes' => 'Simple erupted tooth under local anaesthetic.'],
        'Full Upper Denture' => ['price' => 1200.00, 'unit_label' => 'per_arch',   'notes' => 'Custom acrylic full denture. Includes all fittings.'],
        'Composite Filling' => ['price' => 120.00,  'unit_label' => 'per_tooth',  'notes' => 'Tooth-coloured composite resin.'],
    ];

    public function definition(): array
    {
        $name = $this->faker->randomElement(array_keys(self::$pricing));
        $data = self::$pricing[$name];

        return [
            'price_list_id' => PriceList::factory(),
            'treatment_template_id' => TreatmentTemplate::factory(),
            'variant_name' => null,
            'unit_price' => $data['price'],
            'unit_label' => $data['unit_label'],
            'notes' => $data['notes'],
        ];
    }

    public function implant(): static
    {
        return $this->state([
            'unit_price' => 2500.00,
            'unit_label' => 'per_tooth',
            'notes' => 'Includes implant fixture, abutment, and zirconia crown.',
        ]);
    }

    public function crown(): static
    {
        return $this->state([
            'unit_price' => 850.00,
            'unit_label' => 'per_tooth',
            'notes' => 'Zirconia crown including laboratory fees.',
        ]);
    }

    public function veneer(): static
    {
        return $this->state([
            'unit_price' => 650.00,
            'unit_label' => 'per_tooth',
            'notes' => 'Porcelain veneer including smile design consultation.',
        ]);
    }

    public function whitening(): static
    {
        return $this->state([
            'unit_price' => 350.00,
            'unit_label' => 'fixed',
            'notes' => 'In-clinic whitening session including home maintenance kit.',
        ]);
    }
}
