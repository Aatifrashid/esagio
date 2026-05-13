<?php

namespace Database\Seeders;

use App\Models\ToothChartCondition;
use Database\Factories\ToothChartConditionFactory;
use Illuminate\Database\Seeder;

class ToothChartConditionSeeder extends Seeder
{
    /**
     * Seed all canonical tooth chart conditions.
     * Safe to run multiple times -- skips existing codes.
     */
    public function run(): void
    {
        foreach (ToothChartConditionFactory::CONDITIONS as $condition) {
            ToothChartCondition::firstOrCreate(
                ['code' => $condition['code']],
                [
                    'name' => $condition['name'],
                    'colour' => $condition['colour'],
                    'icon' => $condition['icon'],
                    'description' => $condition['description'],
                    'sort_order' => $condition['sort_order'],
                ]
            );
        }
    }
}
