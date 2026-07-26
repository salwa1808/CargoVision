<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchCountries extends Command
{
    protected $signature = 'fetch:countries';
    protected $description = 'Fetch semua negara dari REST Countries API';

    public function handle(): int
    {
        $this->info('Mengambil data negara dari REST Countries...');

        try {
            $response = Http::timeout(60)
                ->retry(3, 1000)
                ->acceptJson()
                ->get('https://restcountries.com/v3.1/all', [
                    'fields' => 'name,cca2,cca3,capital,region,subregion,currencies,population,latlng,flags,languages',
                ]);
        } catch (Throwable $exception) {
            $this->error('REST Countries tidak dapat dihubungi: '.$exception->getMessage());
            return self::FAILURE;
        }

        if (! $response->successful() || ! is_array($response->json())) {
            $this->error('REST Countries mengembalikan respons yang tidak valid.');
            return self::FAILURE;
        }

        $saved = 0;
        foreach ($response->json() as $item) {
            $cca3 = $item['cca3'] ?? null;
            if (! $cca3) {
                continue;
            }

            $currencyCode = array_key_first($item['currencies'] ?? []);
            $currency = $currencyCode ? ($item['currencies'][$currencyCode] ?? []) : [];

            Country::updateOrCreate(['cca3' => $cca3], [
                'name' => $item['name']['common'] ?? $cca3,
                'official_name' => $item['name']['official'] ?? null,
                'cca2' => $item['cca2'] ?? null,
                'capital' => $item['capital'][0] ?? null,
                'region' => $item['region'] ?? null,
                'subregion' => $item['subregion'] ?? null,
                'currency_code' => $currencyCode,
                'currency_name' => $currency['name'] ?? null,
                'currency_symbol' => $currency['symbol'] ?? null,
                'language' => implode(', ', array_values($item['languages'] ?? [])),
                'population' => $item['population'] ?? null,
                'latitude' => $item['latlng'][0] ?? null,
                'longitude' => $item['latlng'][1] ?? null,
                'flag_png' => $item['flags']['png'] ?? null,
                'flag_svg' => $item['flags']['svg'] ?? null,
            ]);
            $saved++;
        }

        $this->info("{$saved} negara berhasil disimpan.");
        return $saved > 0 ? self::SUCCESS : self::FAILURE;
    }
}
