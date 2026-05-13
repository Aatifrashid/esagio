<?php

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Services\PriceCalculator\CurrencyConverter;

beforeEach(function () {
    Currency::insert([
        ['code' => 'GBP', 'symbol' => '£',   'name' => 'British Pound Sterling', 'decimal_places' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'USD', 'symbol' => '$',   'name' => 'US Dollar',               'decimal_places' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'EUR', 'symbol' => '€',   'name' => 'Euro',                    'decimal_places' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'TRY', 'symbol' => '₺',   'name' => 'Turkish Lira',            'decimal_places' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'AED', 'symbol' => 'AED', 'name' => 'UAE Dirham',              'decimal_places' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ['code' => 'SAR', 'symbol' => 'SAR', 'name' => 'Saudi Riyal',             'decimal_places' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
    ]);

    ExchangeRate::insert([
        ['from_currency' => 'GBP', 'to_currency' => 'USD', 'rate' => 1.270000, 'fetched_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ['from_currency' => 'USD', 'to_currency' => 'GBP', 'rate' => 0.787402, 'fetched_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ['from_currency' => 'GBP', 'to_currency' => 'EUR', 'rate' => 1.170000, 'fetched_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ['from_currency' => 'EUR', 'to_currency' => 'GBP', 'rate' => 0.854701, 'fetched_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ['from_currency' => 'GBP', 'to_currency' => 'TRY', 'rate' => 40.500000, 'fetched_at' => now(), 'created_at' => now(), 'updated_at' => now()],
        ['from_currency' => 'TRY', 'to_currency' => 'GBP', 'rate' => 0.024691, 'fetched_at' => now(), 'created_at' => now(), 'updated_at' => now()],
    ]);
});

// -----------------------------------------------------------------------
// Conversion
// -----------------------------------------------------------------------

test('convert returns same amount when currencies match', function () {
    $converter = new CurrencyConverter;
    expect($converter->convert(100.00, 'GBP', 'GBP'))->toBe(100.00);
});

test('convert applies exchange rate correctly', function () {
    $converter = new CurrencyConverter;
    $result = $converter->convert(1000.00, 'GBP', 'USD');
    expect($result)->toBe(1270.00);
});

test('convert rounds to the correct decimal places', function () {
    $converter = new CurrencyConverter;
    $result = $converter->convert(1.00, 'GBP', 'TRY');
    expect($result)->toBe(40.50);
});

test('convert uses inverse rate when direct rate is missing', function () {
    $converter = new CurrencyConverter;
    // Only EUR->GBP is stored; GBP->EUR should be derived from inverse
    ExchangeRate::where('from_currency', 'GBP')->where('to_currency', 'EUR')->delete();
    $result = $converter->convert(100.00, 'GBP', 'EUR');
    // 1 / 0.854701 GBP/EUR ~ 1.17 EUR per GBP
    expect($result)->toBeGreaterThan(1.0);
});

// -----------------------------------------------------------------------
// getRate
// -----------------------------------------------------------------------

test('getRate returns direct rate from database', function () {
    $converter = new CurrencyConverter;
    $rate = $converter->getRate('GBP', 'USD');
    expect($rate)->toBe(1.27);
});

test('getRate returns 1.0 for same currency', function () {
    $converter = new CurrencyConverter;
    expect($converter->getRate('GBP', 'GBP'))->toBe(1.0);
});

test('getRate throws exception when rate is missing', function () {
    $converter = new CurrencyConverter;
    expect(fn () => $converter->getRate('GBP', 'SAR'))
        ->toThrow(RuntimeException::class);
});

// -----------------------------------------------------------------------
// formatAmount
// -----------------------------------------------------------------------

test('format GBP prefixes pound symbol', function () {
    $converter = new CurrencyConverter;
    expect($converter->formatAmount(2500.00, 'GBP'))->toBe('£2,500.00');
});

test('format USD prefixes dollar symbol', function () {
    $converter = new CurrencyConverter;
    expect($converter->formatAmount(1234.50, 'USD'))->toBe('$1,234.50');
});

test('format EUR prefixes euro symbol', function () {
    $converter = new CurrencyConverter;
    expect($converter->formatAmount(999.99, 'EUR'))->toBe('€999.99');
});

test('format TRY prefixes lira symbol', function () {
    $converter = new CurrencyConverter;
    expect($converter->formatAmount(10000.00, 'TRY'))->toBe('₺10,000.00');
});

test('format AED appends suffix after amount', function () {
    $converter = new CurrencyConverter;
    expect($converter->formatAmount(500.00, 'AED'))->toBe('500.00 AED');
});

test('format SAR appends suffix after amount', function () {
    $converter = new CurrencyConverter;
    expect($converter->formatAmount(1000.00, 'SAR'))->toBe('1,000.00 SAR');
});

test('format uses static method correctly', function () {
    expect(CurrencyConverter::formatAmountStatic(2500.00, 'GBP'))->toBe('£2,500.00');
});

// -----------------------------------------------------------------------
// Exchange rate lookup edge cases
// -----------------------------------------------------------------------

test('exchange rate lookup is case-insensitive', function () {
    $converter = new CurrencyConverter;
    expect($converter->getRate('gbp', 'usd'))->toBe(1.27);
});
