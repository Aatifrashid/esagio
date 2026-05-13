<?php

namespace Database\Factories;

use App\Models\CrmPipeline;
use App\Models\CrmPipelineStage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CrmPipelineStage>
 */
class CrmPipelineStageFactory extends Factory
{
    /**
     * Ordered consultation pipeline stages with realistic probabilities and colours.
     */
    public const CONSULTATION_STAGES = [
        ['name' => 'New Lead',           'colour' => '#94A3B8', 'probability_percentage' => 10,  'is_won' => false, 'is_lost' => false, 'sort_order' => 1],
        ['name' => 'Contacted',          'colour' => '#60A5FA', 'probability_percentage' => 20,  'is_won' => false, 'is_lost' => false, 'sort_order' => 2],
        ['name' => 'Consultation Booked', 'colour' => '#34D399', 'probability_percentage' => 40,  'is_won' => false, 'is_lost' => false, 'sort_order' => 3],
        ['name' => 'Plan Sent',          'colour' => '#FBBF24', 'probability_percentage' => 55,  'is_won' => false, 'is_lost' => false, 'sort_order' => 4],
        ['name' => 'Negotiating',        'colour' => '#F97316', 'probability_percentage' => 70,  'is_won' => false, 'is_lost' => false, 'sort_order' => 5],
        ['name' => 'Won',                'colour' => '#22C55E', 'probability_percentage' => 100, 'is_won' => true,  'is_lost' => false, 'sort_order' => 6],
        ['name' => 'Lost',               'colour' => '#EF4444', 'probability_percentage' => 0,   'is_won' => false, 'is_lost' => true,  'sort_order' => 7],
    ];

    public const TREATMENT_STAGES = [
        ['name' => 'Plan Accepted',      'colour' => '#34D399', 'probability_percentage' => 80,  'is_won' => false, 'is_lost' => false, 'sort_order' => 1],
        ['name' => 'Deposit Paid',       'colour' => '#22C55E', 'probability_percentage' => 90,  'is_won' => false, 'is_lost' => false, 'sort_order' => 2],
        ['name' => 'Treatment Ongoing',  'colour' => '#3B82F6', 'probability_percentage' => 95,  'is_won' => false, 'is_lost' => false, 'sort_order' => 3],
        ['name' => 'Treatment Complete', 'colour' => '#8B5CF6', 'probability_percentage' => 100, 'is_won' => true,  'is_lost' => false, 'sort_order' => 4],
        ['name' => 'Cancelled',          'colour' => '#EF4444', 'probability_percentage' => 0,   'is_won' => false, 'is_lost' => true,  'sort_order' => 5],
    ];

    public function definition(): array
    {
        $stage = $this->faker->randomElement(self::CONSULTATION_STAGES);

        return [
            'crm_pipeline_id' => CrmPipeline::factory(),
            'name' => $stage['name'],
            'colour' => $stage['colour'],
            'probability_percentage' => $stage['probability_percentage'],
            'sort_order' => $stage['sort_order'],
            'is_won' => $stage['is_won'],
            'is_lost' => $stage['is_lost'],
        ];
    }

    public function newLead(): static
    {
        return $this->state([
            'name' => 'New Lead',
            'colour' => '#94A3B8',
            'probability_percentage' => 10,
            'sort_order' => 1,
            'is_won' => false,
            'is_lost' => false,
        ]);
    }

    public function won(): static
    {
        return $this->state([
            'name' => 'Won',
            'colour' => '#22C55E',
            'probability_percentage' => 100,
            'is_won' => true,
            'is_lost' => false,
        ]);
    }

    public function lost(): static
    {
        return $this->state([
            'name' => 'Lost',
            'colour' => '#EF4444',
            'probability_percentage' => 0,
            'is_won' => false,
            'is_lost' => true,
        ]);
    }
}
