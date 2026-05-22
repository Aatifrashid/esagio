<?php

/**
 * One-time Opus Smile Turkey price list seeder — DELETE after use.
 */

$secret = 'esa_seed_2026_p7r3q';

if (($_GET['token'] ?? '') !== $secret) {
    http_response_code(403);
    die('Forbidden');
}

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TreatmentCategory;
use App\Models\TreatmentTemplate;
use App\Models\PriceList;
use App\Models\PriceListItem;

echo "<pre>\n";

// Get the clinic ID (first clinic)
$clinicId = \App\Models\Clinic::first()?->id;
if (!$clinicId) {
    die("No clinic found.\n");
}

echo "Clinic ID: $clinicId\n\n";

// Create treatment categories
$categories = [
    'Crowns & Veneers' => null,
    'General Dentistry' => null,
    'Implants' => null,
    'Oral Surgery' => null,
    'Cosmetic' => null,
    'Hygiene' => null,
    'Packages' => null,
    'Prosthetics' => null,
];

foreach ($categories as $name => &$cat) {
    $cat = TreatmentCategory::firstOrCreate(
        ['name' => $name, 'clinic_id' => $clinicId],
        ['name' => $name, 'clinic_id' => $clinicId]
    );
    echo "Category: {$cat->name} (ID: {$cat->id})\n";
}
unset($cat);

// Define all treatments with category, name, price, unit
$treatments = [
    // Crowns & Veneers
    ['Crowns & Veneers', 'Metal Porcelain Crown', 100, 'per_tooth'],
    ['Crowns & Veneers', 'Composite Bonding', 140, 'per_tooth'],
    ['Crowns & Veneers', 'Zirconia Crown', 170, 'per_tooth'],
    ['Crowns & Veneers', 'E-max Crown', 200, 'per_tooth'],
    ['Crowns & Veneers', 'E-max Laminate', 210, 'per_tooth'],
    ['Crowns & Veneers', 'ZirCad Prime Kron', 200, 'per_tooth'],

    // Cosmetic
    ['Cosmetic', 'Bleaching', 200, 'per_session'],
    ['Cosmetic', 'Root Bleaching', 120, 'per_tooth'],
    ['Cosmetic', 'Gum Contouring', 50, 'per_tooth'],
    ['Cosmetic', 'Botox', 225, 'per_session'],

    // General Dentistry
    ['General Dentistry', 'Filling', 75, 'per_tooth'],
    ['General Dentistry', 'Root Canal Treatment', 120, 'per_tooth'],
    ['General Dentistry', 'Night Guard', 120, 'per_jaw'],
    ['General Dentistry', 'Fibre', 85, 'per_unit'],

    // Hygiene
    ['Hygiene', 'Cleaning', 60, 'per_session'],
    ['Hygiene', 'Airflow Cleaning', 100, 'per_session'],
    ['Hygiene', 'Deep Cleaning', 110, 'per_jaw'],

    // Oral Surgery
    ['Oral Surgery', 'Extraction', 60, 'per_tooth'],
    ['Oral Surgery', 'Surgical Extraction', 120, 'per_tooth'],
    ['Oral Surgery', 'Wisdom Tooth Extraction (Partially Impacted)', 210, 'per_tooth'],
    ['Oral Surgery', 'Wisdom Tooth Extraction (Fully Impacted)', 160, 'per_tooth'],
    ['Oral Surgery', 'Closed Sinus Lift', 150, 'per_pocket'],
    ['Oral Surgery', 'Open Sinus Lift + Membrane', 365, 'per_pocket'],
    ['Oral Surgery', 'Bone Graft', 120, 'per_cc'],
    ['Oral Surgery', 'Frenectomy', 175, 'per_procedure'],

    // Implants
    ['Implants', 'NTA Spure Implant', 300, 'per_implant'],
    ['Implants', 'NTA Implant', 410, 'per_implant'],
    ['Implants', 'NTA Shorter Implant', 550, 'per_implant'],
    ['Implants', 'NTA Hybrid Implant', 550, 'per_implant'],
    ['Implants', 'Straumann BLX Implant', 750, 'per_implant'],

    // Prosthetics
    ['Prosthetics', 'All on X Temporary Denture (PMMA)', 500, 'per_jaw'],
    ['Prosthetics', 'Trilor Bar', 550, 'per_jaw'],
    ['Prosthetics', 'Titanium Bar', 650, 'per_jaw'],
    ['Prosthetics', 'Toronto Bar with Trilor', 700, 'per_jaw'],
    ['Prosthetics', 'Abutment (NTA Compatible)', 95, 'per_unit'],
    ['Prosthetics', 'Abutment (All Brands)', 110, 'per_unit'],

    // Packages
    ['Packages', 'All-on-4 (NTA Spure)', 3099, 'per_arch'],
    ['Packages', 'All-on-4 (NTA)', 3150, 'per_arch'],
    ['Packages', 'All-on-4 (Straumann)', 4400, 'per_arch'],
    ['Packages', 'All-on-6 (NTA Spure)', 3950, 'per_arch'],
    ['Packages', 'All-on-6 (NTA)', 4150, 'per_arch'],
    ['Packages', 'All-on-6 (Straumann)', 5750, 'per_arch'],
];

// Get or update price list
$priceList = PriceList::where('clinic_id', $clinicId)->first();
if ($priceList) {
    $priceList->update(['name' => 'Opus Smile Turkey 2026']);
    echo "\nUpdated price list: {$priceList->name}\n";
} else {
    $priceList = PriceList::create([
        'name' => 'Opus Smile Turkey 2026',
        'currency' => 'GBP',
        'is_default' => true,
        'valid_from' => '2026-01-01',
        'valid_until' => '2026-12-31',
        'notes' => 'Opus Smile Turkey pricing. All prices in GBP. Inclusive of laboratory fees.',
        'clinic_id' => $clinicId,
    ]);
    echo "\nCreated price list: {$priceList->name}\n";
}

// Delete existing price list items
$deleted = PriceListItem::where('price_list_id', $priceList->id)->delete();
echo "Cleared $deleted old price list items.\n\n";

// Create templates and price list items
$count = 0;
foreach ($treatments as [$catName, $name, $price, $unit]) {
    $category = $categories[$catName];

    $template = TreatmentTemplate::firstOrCreate(
        ['name' => $name, 'clinic_id' => $clinicId],
        [
            'category_id' => $category->id,
            'name' => $name,
            'code' => strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 6)),
            'is_active' => true,
            'clinic_id' => $clinicId,
        ]
    );

    PriceListItem::create([
        'price_list_id' => $priceList->id,
        'treatment_template_id' => $template->id,
        'unit_price' => $price,
        'unit_label' => $unit,
    ]);

    echo "  + {$name} — £{$price} ({$unit})\n";
    $count++;
}

echo "\n--- Done! Added $count treatments to price list. ---\n";
echo "\n-- DELETE this file immediately: public/seed-pricelist.php --\n";
echo "</pre>";
