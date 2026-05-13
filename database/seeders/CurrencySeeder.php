<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'GBP', 'symbol' => '£',   'name' => 'British Pound Sterling', 'decimal_places' => 2],
            ['code' => 'USD', 'symbol' => '$',   'name' => 'US Dollar',               'decimal_places' => 2],
            ['code' => 'EUR', 'symbol' => '€',   'name' => 'Euro',                    'decimal_places' => 2],
            ['code' => 'TRY', 'symbol' => '₺',   'name' => 'Turkish Lira',            'decimal_places' => 2],
            ['code' => 'AED', 'symbol' => 'AED', 'name' => 'UAE Dirham',              'decimal_places' => 2],
            ['code' => 'SAR', 'symbol' => 'SAR', 'name' => 'Saudi Riyal',             'decimal_places' => 2],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                array_merge($currency, ['is_active' => true])
            );
        }
    }
}
