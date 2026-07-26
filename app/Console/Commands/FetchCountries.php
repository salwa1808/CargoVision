<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class FetchCountries extends Command
{
    protected $signature = 'fetch:countries';
    protected $description = 'Fetch semua negara dari dataset publik';

    public function handle(): int
    {
        $this->info('Mengambil data negara dari dataset publik...');

        try {
            $response = Http::timeout(60)
                ->retry(3, 1000)
                ->acceptJson()
                ->get('https://raw.githubusercontent.com/mledoze/countries/master/countries.json');
        } catch (Throwable $exception) {
            $this->error('Dataset negara tidak dapat dihubungi: '.$exception->getMessage());
            return self::FAILURE;
        }

        $countries = $response->json();

        if (! $response->successful() || ! is_array($countries) || ! array_is_list($countries)) {
            $this->error('Dataset negara mengembalikan respons yang tidak valid.');
            return self::FAILURE;
        }

        $saved = 0;
        foreach ($countries as $item) {
            $cca3 = $item['cca3'] ?? null;
            if (! $cca3) {
                continue;
            }

            $cca2 = $item['cca2'] ?? null;
            $currencyCode = array_key_first($item['currencies'] ?? []);
            $currency = $currencyCode ? ($item['currencies'][$currencyCode] ?? []) : [];

            Country::updateOrCreate(['cca3' => $cca3], [
                'name' => $item['name']['common'] ?? $cca3,
                'official_name' => $item['name']['official'] ?? null,
                'cca2' => $cca2,
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
                'flag_png' => $cca2
                    ? 'https://flags.restcountries.com/v5/w320/'.strtolower($cca2).'.png'
                    : null,
                'flag_svg' => null,
            ]);
            $saved++;
        }

        $this->info("{$saved} negara berhasil disimpan.");
        return $saved > 0 ? self::SUCCESS : self::FAILURE;
    }
}
