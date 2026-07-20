<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\ExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchExchangeRates extends Command
{
    protected $signature = 'fetch:exchangerates';

    protected $description = 'Mengambil kurs mata uang dari open.er-api.com';

    public function handle()
    {
        $this->info('Mengambil data kurs...');

        try {

            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->acceptJson()
                ->get('https://open.er-api.com/v6/latest/USD');

        } catch (\Exception $e) {

            $this->error('Tidak dapat terhubung ke API.');

            return Command::FAILURE;
        }

        if (!$response->successful()) {

            $this->error('API gagal diakses.');

            return Command::FAILURE;
        }

        $rates = $response->json()['rates'] ?? [];

        $success = 0;
        $failed = 0;

        $countries = Country::orderBy('id')->get();

        foreach ($countries as $country) {

            if (empty($country->currency_code)) {

                $failed++;
                continue;
            }

            if (!isset($rates[$country->currency_code])) {

                $this->warn($country->name . ' -> Kurs tidak ditemukan');

                $failed++;
                continue;
            }

            ExchangeRate::create([

                'country_id' => $country->id,

                'currency_code' => $country->currency_code,

                'exchange_rate' => $rates[$country->currency_code],

            ]);

            $this->info($country->name . ' ✔');

            $success++;
        }

        $this->newLine();

        $this->info("==============================");
        $this->info("Berhasil : {$success}");
        $this->warn("Tidak ditemukan : {$failed}");
        $this->info("==============================");

        return Command::SUCCESS;
    }
}