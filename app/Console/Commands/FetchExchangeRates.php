<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchExchangeRates extends Command
{
    protected $signature = 'exchange:fetch
                            {--base=GBP : The base currency to fetch rates from}
                            {--dry-run : Show what would be updated without saving}';

    protected $description = 'Fetch current exchange rates from exchangerate.host and update the exchange_rates table';

    public function handle(): int
    {
        $base = strtoupper($this->option('base'));
        $isDryRun = (bool) $this->option('dry-run');

        $activeCurrencies = Currency::where('is_active', true)->pluck('code')->toArray();

        if (empty($activeCurrencies)) {
            $this->error('No active currencies found. Run the CurrencySeeder first.');

            return self::FAILURE;
        }

        $this->info("Fetching exchange rates from base currency: {$base}");

        $apiKey = config('services.exchangerate_host.key', '');
        $url = 'https://api.exchangerate.host/live';

        $params = [
            'source' => $base,
            'currencies' => implode(',', $activeCurrencies),
            'format' => 1,
        ];

        if ($apiKey) {
            $params['access_key'] = $apiKey;
        }

        try {
            $response = Http::timeout(15)->get($url, $params);

            if (! $response->successful()) {
                $this->error('API request failed with status: '.$response->status());
                Log::error('FetchExchangeRates: API error', ['status' => $response->status(), 'body' => $response->body()]);

                return self::FAILURE;
            }

            $data = $response->json();
        } catch (\Throwable $e) {
            $this->error('HTTP request failed: '.$e->getMessage());
            Log::error('FetchExchangeRates: exception', ['error' => $e->getMessage()]);

            return self::FAILURE;
        }

        if (empty($data['quotes'])) {
            $this->error('No quotes returned from API. Response: '.json_encode($data));

            return self::FAILURE;
        }

        $quotes = $data['quotes'];
        $updated = 0;
        $fetchedAt = now();

        foreach ($activeCurrencies as $to) {
            if ($to === $base) {
                continue;
            }

            $key = $base.$to;
            $altKey = strtoupper($base).strtoupper($to);

            $rate = $quotes[$key] ?? $quotes[$altKey] ?? null;

            if (! $rate) {
                $this->warn("No rate found for {$base} to {$to}");

                continue;
            }

            if ($isDryRun) {
                $this->line("  [dry-run] {$base} -> {$to}: {$rate}");

                continue;
            }

            ExchangeRate::updateOrCreate(
                ['from_currency' => $base, 'to_currency' => $to],
                ['rate' => $rate, 'fetched_at' => $fetchedAt]
            );

            // Store the inverse as well
            ExchangeRate::updateOrCreate(
                ['from_currency' => $to, 'to_currency' => $base],
                ['rate' => round(1 / (float) $rate, 6), 'fetched_at' => $fetchedAt]
            );

            $updated++;
            $this->line("  Updated {$base} -> {$to}: {$rate}");
        }

        if (! $isDryRun) {
            $this->info("Exchange rates updated: {$updated} pairs (plus inverses).");
            Log::info("FetchExchangeRates: updated {$updated} pairs", ['base' => $base, 'at' => $fetchedAt]);
        }

        return self::SUCCESS;
    }
}
