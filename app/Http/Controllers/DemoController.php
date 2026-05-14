<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DemoController extends Controller
{
    public function show(Request $request): View
    {
        $demoClinic = [
            'id' => 0,
            'name' => 'Esagio Demo Clinic',
            'email' => 'demo@esagio.com',
            'phone' => '+44 20 7946 0958',
            'country' => 'GB',
            'timezone' => 'Europe/London',
            'currency_default' => 'GBP',
            'language_default' => 'en',
            'primary_colour' => '#0A2540',
            'secondary_colour' => '#E8663D',
        ];

        $demoPatient = [
            'id' => 0,
            'first_name' => 'Sarah',
            'last_name' => 'Thompson',
            'email' => 'sarah.thompson@example.com',
            'phone' => '+44 7700 900123',
            'date_of_birth' => '1988-03-15',
        ];

        $toothChartConditions = [
            [
                'tooth_number' => 36,
                'conditions' => ['decay'],
                'planned_treatments' => ['composite_filling'],
                'notes' => 'Moderate decay on the occlusal surface requiring composite restoration.',
            ],
            [
                'tooth_number' => 46,
                'conditions' => ['missing'],
                'planned_treatments' => ['implant'],
                'notes' => 'Missing tooth, suitable for single implant placement.',
            ],
            [
                'tooth_number' => 11,
                'conditions' => ['crown_needed'],
                'planned_treatments' => ['crown'],
                'notes' => 'Fractured incisal edge, full ceramic crown recommended.',
            ],
        ];

        $treatmentItems = [
            [
                'name' => 'Composite Filling (Tooth 36)',
                'description' => 'Direct composite restoration to treat decay on lower left first molar. Tooth-coloured material matched to natural shade.',
                'quantity' => 1,
                'unit_price' => 195.00,
                'line_total' => 195.00,
                'tooth_positions' => [36],
                'is_optional' => false,
            ],
            [
                'name' => 'Dental Implant (Tooth 46)',
                'description' => 'Titanium implant placement with healing abutment for the missing lower right first molar. Includes surgical guide and follow-up appointments.',
                'quantity' => 1,
                'unit_price' => 2450.00,
                'line_total' => 2450.00,
                'tooth_positions' => [46],
                'is_optional' => false,
            ],
            [
                'name' => 'Porcelain Crown (Tooth 11)',
                'description' => 'Full ceramic crown for the upper right central incisor. Shade-matched and designed for optimal aesthetics.',
                'quantity' => 1,
                'unit_price' => 850.00,
                'line_total' => 850.00,
                'tooth_positions' => [11],
                'is_optional' => false,
            ],
            [
                'name' => 'Professional Teeth Whitening',
                'description' => 'In-surgery LED-activated whitening treatment. Includes take-home maintenance kit with custom trays.',
                'quantity' => 1,
                'unit_price' => 350.00,
                'line_total' => 350.00,
                'tooth_positions' => [],
                'is_optional' => true,
            ],
        ];

        $beforeAfterImages = [
            [
                'title' => 'Crown Restoration',
                'before_url' => '/images/demo/before-crown.jpg',
                'after_url' => '/images/demo/after-crown.jpg',
                'description' => 'Upper central incisor restored with porcelain crown.',
            ],
            [
                'title' => 'Implant Placement',
                'before_url' => '/images/demo/before-implant.jpg',
                'after_url' => '/images/demo/after-implant.jpg',
                'description' => 'Missing molar replaced with a single dental implant.',
            ],
        ];

        $totalAmount = collect($treatmentItems)->where('is_optional', false)->sum('line_total');

        return view('demo.show', [
            'demoClinic' => $demoClinic,
            'demoPatient' => $demoPatient,
            'toothChartConditions' => $toothChartConditions,
            'treatmentItems' => $treatmentItems,
            'beforeAfterImages' => $beforeAfterImages,
            'totalAmount' => $totalAmount,
            'isDemoMode' => true,
        ]);
    }
}
