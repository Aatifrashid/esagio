<?php

namespace Database\Factories;

use App\Models\AnimationClip;
use App\Models\TreatmentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnimationClip>
 */
class AnimationClipFactory extends Factory
{
    private static array $clips = [
        [
            'code' => 'ANIM-IMP-001',
            'name' => 'Single Implant Placement Procedure',
            'description' => '3D animation showing the step-by-step placement of a single dental implant fixture, healing cap, abutment, and final crown.',
            'procedure_type' => 'implant',
            'jaw' => 'both',
            'duration_seconds' => 90,
        ],
        [
            'code' => 'ANIM-IMP-002',
            'name' => 'All-on-4 Full Arch Restoration',
            'description' => '3D animation demonstrating the All-on-4 surgical protocol including implant angulation, temporary prosthesis connection, and final prosthesis fitting.',
            'procedure_type' => 'implant',
            'jaw' => 'upper',
            'duration_seconds' => 120,
        ],
        [
            'code' => 'ANIM-IMP-003',
            'name' => 'Implant Osseointegration Process',
            'description' => 'Time-lapse style animation showing bone growth and osseointegration around a titanium implant fixture over a 3-month healing period.',
            'procedure_type' => 'implant',
            'jaw' => 'both',
            'duration_seconds' => 60,
        ],
        [
            'code' => 'ANIM-CRN-001',
            'name' => 'Crown Preparation and Fitting',
            'description' => 'Animation showing tooth preparation, impression, temporary crown placement, and final permanent crown cementation.',
            'procedure_type' => 'crown',
            'jaw' => 'upper',
            'duration_seconds' => 75,
        ],
        [
            'code' => 'ANIM-VEN-001',
            'name' => 'Porcelain Veneer Application',
            'description' => 'Step-by-step animation of veneer preparation, etching, bonding agent application, and final veneer placement on upper anterior teeth.',
            'procedure_type' => 'veneer',
            'jaw' => 'upper',
            'duration_seconds' => 65,
        ],
        [
            'code' => 'ANIM-EXT-001',
            'name' => 'Simple Tooth Extraction',
            'description' => 'Animation demonstrating the elevation and forceps extraction of a lower molar under local anaesthetic.',
            'procedure_type' => 'extraction',
            'jaw' => 'lower',
            'duration_seconds' => 45,
        ],
        [
            'code' => 'ANIM-EXT-002',
            'name' => 'Surgical Wisdom Tooth Removal',
            'description' => 'Detailed animation of a lower right impacted wisdom tooth surgical extraction including flap elevation, bone removal, and sectioning.',
            'procedure_type' => 'extraction',
            'jaw' => 'lower',
            'duration_seconds' => 90,
        ],
        [
            'code' => 'ANIM-RCT-001',
            'name' => 'Root Canal Treatment Procedure',
            'description' => 'Animation showing access cavity preparation, pulp removal, canal shaping, irrigation, and obturation of an upper molar.',
            'procedure_type' => 'root_canal',
            'jaw' => 'upper',
            'duration_seconds' => 80,
        ],
        [
            'code' => 'ANIM-WHT-001',
            'name' => 'In-clinic Teeth Whitening Process',
            'description' => 'Animation demonstrating gum protection, whitening gel application, LED light activation, and shade improvement.',
            'procedure_type' => 'whitening',
            'jaw' => 'both',
            'duration_seconds' => 50,
        ],
        [
            'code' => 'ANIM-ORT-001',
            'name' => 'Clear Aligner Teeth Movement',
            'description' => 'Time-lapse animation showing progressive tooth movement across 20 aligner stages from initial crowding to final alignment.',
            'procedure_type' => 'orthodontics',
            'jaw' => 'both',
            'duration_seconds' => 60,
        ],
        [
            'code' => 'ANIM-PER-001',
            'name' => 'Periodontal Deep Cleaning (Scaling and Root Planing)',
            'description' => 'Animation illustrating subgingival calculus removal, root planing, and tissue reattachment following non-surgical periodontal treatment.',
            'procedure_type' => 'periodontal',
            'jaw' => 'both',
            'duration_seconds' => 55,
        ],
    ];

    public function definition(): array
    {
        $clip = $this->faker->randomElement(self::$clips);

        return [
            'clinic_id' => null,
            'code' => $clip['code'],
            'name' => $clip['name'],
            'description' => $clip['description'],
            'treatment_category_id' => TreatmentCategory::factory(),
            'procedure_type' => $clip['procedure_type'],
            'tooth_positions' => null,
            'jaw' => $clip['jaw'],
            'duration_seconds' => $clip['duration_seconds'],
            'video_path' => null,
            'thumbnail_path' => null,
            'sort_order' => 0,
        ];
    }

    public function implant(): static
    {
        return $this->state([
            'code' => 'ANIM-IMP-001',
            'name' => 'Single Implant Placement Procedure',
            'procedure_type' => 'implant',
        ]);
    }
}
