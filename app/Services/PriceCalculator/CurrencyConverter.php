<?php

namespace App\Services\PriceCalculator;

use App\Models\Currency;
use App\Models\ExchangeRate;
use RuntimeException;

class CurrencyConverter
{
    /**
     * Convert an amount from one currency to another.
     *
     * @throws RuntimeException if no exchange rate is found
     */
    public function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return round($amount, $this->decimalPlaces($to));
        }

        $rate = $this->getRate($from, $to);

        return round($amount * $rate, $this->decimalPlaces($to));
    }

    /**
     * Retrieve the exchange rate from DB.
     *
     * Tries the direct pair first, then the inverse rate.
     *
     * @throws RuntimeException if the rate is not available
     */
    public function getRate(string $from, string $to): float
    {
        if ($from === $to) {
            return 1.0;
        }

        $direct = ExchangeRate::where('from_currency', strtoupper($from))
            ->where('to_currency', strtoupper($to))
            ->first();

        if ($direct) {
            return (float) $direct->rate;
        }

        // Try inverse
        $inverse = ExchangeRate::where('from_currency', strtoupper($to))
            ->where('to_currency', strtoupper($from))
            ->first();

        if ($inverse && $inverse->rate > 0) {
            return round(1 / (float) $inverse->rate, 6);
        }

        throw new RuntimeException(
            "No exchange rate found for {$from} to {$to}. Run 'php artisan exchange:fetch' to populate rates."
        );
    }

    /**
     * Format an amount as a localised currency string.
     *
     * Examples:
     *   GBP 2500.00  -> "£2,500.00"
     *   USD 1234.5   -> "$1,234.50"
     *   EUR 999.99   -> "€999.99"
     *   TRY 10000.0  -> "₺10,000.00"
     */
    public function formatAmount(float $amount, string $currencyCode): string
    {
        return self::formatAmountStatic($amount, $currencyCode);
    }

    /**
     * Static helper so it can be called from Blade views without injecting the service.
     */
    public static function formatAmountStatic(float $amount, string $currencyCode): string
    {
        $currency = Currency::where('code', strtoupper($currencyCode))->first();

        $symbol = $currency?->symbol ?? strtoupper($currencyCode).' ';
        $decimals = $currency?->decimal_places ?? 2;

        $formatted = number_format(round($amount, $decimals), $decimals);

        // Codes where symbol appears after the number
        $suffixCodes = ['SAR', 'AED'];

        if (in_array(strtoupper($currencyCode), $suffixCodes)) {
            return $formatted.' '.$symbol;
        }

        return $symbol.$formatted;
    }

    private function decimalPlaces(string $currencyCode): int
    {
        $currency = Currency::where('code', strtoupper($currencyCode))->first();

        return $currency?->decimal_places ?? 2;
    }
}
