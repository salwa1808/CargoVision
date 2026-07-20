<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\WeatherSnapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchWeather extends Command
{
    protected $signature = 'fetch:weather';

    protected $description = 'Mengambil data cuaca dari Open-Meteo';

    public function handle()
    {
        $this->info("==============================");
        $this->info("FETCH WEATHER");
        $this->info("==============================");

        $success = 0;
        $failed = 0;

        $countries = Country::orderBy('id')->get();

        foreach ($countries as $country) {

            if (!$country->latitude || !$country->longitude) {

                $this->warn($country->name . " -> Koordinat tidak tersedia");

                $failed++;

                continue;
            }

            $url = "https://api.open-meteo.com/v1/forecast?latitude={$country->latitude}&longitude={$country->longitude}&current=temperature_2m,wind_speed_10m,rain";

            try {

                $response = Http::timeout(20)
                    ->retry(2, 1000)
                    ->acceptJson()
                    ->get($url);

            } catch (\Exception $e) {

                $this->error($country->name . " -> Timeout");

                $failed++;

                continue;
            }

            if (!$response->successful()) {

                $this->error($country->name . " -> Gagal mengambil data");

                $failed++;

                continue;
            }

            $current = $response->json()['current'] ?? [];

            $temperature = $current['temperature_2m'] ?? 0;

            $windSpeed = $current['wind_speed_10m'] ?? 0;

            $rainfall = $current['rain'] ?? 0;

            /*
            |--------------------------------------------------------------------------
            | Storm Risk
            |--------------------------------------------------------------------------
            */

            if ($windSpeed >= 35 || $rainfall >= 15) {

                $stormRisk = 'high';

            } elseif ($windSpeed >= 20 || $rainfall >= 5) {

                $stormRisk = 'medium';

            } else {

                $stormRisk = 'low';
            }

            WeatherSnapshot::updateOrCreate(

                [

                    'country_id' => $country->id

                ],

                [

                    'temperature' => $temperature,

                    'wind_speed' => $windSpeed,

                    'rainfall' => $rainfall,

                    'storm_risk' => $stormRisk

                ]

            );

            $this->line(

                $country->name .
                " | Temp : {$temperature}°C" .
                " | Wind : {$windSpeed}" .
                " | Rain : {$rainfall}" .
                " | Risk : {$stormRisk}"

            );

            $success++;

            usleep(150000);

        }

        $this->newLine();

        $this->info("==============================");
        $this->info("WEATHER UPDATE FINISHED");
        $this->info("==============================");
        $this->info("Berhasil : {$success}");
        $this->warn("Gagal : {$failed}");
        $this->info("==============================");

        return Command::SUCCESS;
    }
}