<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\EconomicIndicator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchEconomic extends Command
{
    protected $signature = 'fetch:economic';

    protected $description = 'Mengambil data ekonomi dari World Bank API';

    public function handle()
    {
        $this->info('Mengambil data ekonomi...');

        $success = 0;
        $failed = 0;
        $skipped = 0;

        // Ambil semua negara
        $countries = Country::orderBy('id')->get();

        foreach ($countries as $country) {

            // Skip jika sudah pernah diambil
            if (EconomicIndicator::where('country_id', $country->id)->exists()) {
                $this->line($country->name . ' -> Sudah ada');
                $skipped++;
                continue;
            }

            if (empty($country->cca3)) {
                $failed++;
                continue;
            }

            $gdp = $this->getIndicator($country->cca3, 'NY.GDP.MKTP.CD');
            $inflation = $this->getIndicator($country->cca3, 'FP.CPI.TOTL.ZG');
            $population = $this->getIndicator($country->cca3, 'SP.POP.TOTL');

            if (
                is_null($gdp['value']) &&
                is_null($inflation['value']) &&
                is_null($population['value'])
            ) {
                $this->warn($country->name . ' -> Tidak ada data');
                $failed++;
                continue;
            }

            EconomicIndicator::create([
                'country_id' => $country->id,
                'gdp' => $gdp['value'],
                'inflation' => $inflation['value'],
                'population' => $population['value'],
                'year' => $gdp['year'] ?? $population['year'] ?? $inflation['year'],
            ]);

            $this->info($country->name . ' ✔');

            $success++;

            // Delay 0.3 detik supaya tidak terlalu membebani API
            usleep(300000);
        }

        $this->newLine();

        $this->info("==============================");
        $this->info("Berhasil      : {$success}");
        $this->line("Dilewati      : {$skipped}");
        $this->warn("Tidak ada data: {$failed}");
        $this->info("==============================");

        return Command::SUCCESS;
    }

    private function getIndicator($countryCode, $indicator)
    {
        $url = "https://api.worldbank.org/v2/country/{$countryCode}/indicator/{$indicator}?format=json&per_page=1";

        try {

            $response = Http::timeout(60)
                ->retry(3, 1000)
                ->acceptJson()
                ->get($url);

        } catch (\Exception $e) {

            return [
                'value' => null,
                'year' => null,
            ];
        }

        if (!$response->successful()) {

            return [
                'value' => null,
                'year' => null,
            ];
        }

        $json = $response->json();

        if (!isset($json[1][0])) {

            return [
                'value' => null,
                'year' => null,
            ];
        }

        return [
            'value' => $json[1][0]['value'] ?? null,
            'year' => $json[1][0]['date'] ?? null,
        ];
    }
}