<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCountries extends Command
{
    protected $signature = 'fetch:countries';

    protected $description = 'Fetch semua negara dari countries.dev';

    public function handle()
    {
        $this->info('Mengambil data negara...');

        $response = Http::get('https://countries.dev/countries');

        if (!$response->successful()) {

            $this->error('Gagal mengambil data.');

            return Command::FAILURE;
        }

        $countries = $response->json();

        foreach ($countries as $item) {

            Country::updateOrCreate(

                [
                    'cca3' => $item['alpha3Code']
                ],

                [

                    'name' => $item['name'] ?? null,

                    'cca3' => $item['alpha3Code'] ?? null,

                    'cca2' => $item['alpha2Code'] ?? null,

                    'capital' => $item['capital'] ?? null,

                    'region' => $item['region'] ?? null,

                    'subregion' => $item['subregion'] ?? null,

                    'currency_code' => $item['currencies'][0]['code'] ?? null,

                    'currency_name' => $item['currencies'][0]['name'] ?? null,

                    'currency_symbol' => $item['currencies'][0]['symbol'] ?? null,

                    'population' => $item['population'] ?? null,

                    'latitude' => $item['latlng'][0] ?? null,

                    'longitude' => $item['latlng'][1] ?? null,

                    'flag_png' => $item['flags']['png'] ?? null,

                    'flag_svg' => $item['flags']['svg'] ?? null,

                ]

            );

        }

        $this->info(count($countries).' negara berhasil disimpan.');

        return Command::SUCCESS;
    }
}
