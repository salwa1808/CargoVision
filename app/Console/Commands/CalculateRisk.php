<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\EconomicIndicator;
use App\Models\ExchangeRate;
use App\Models\NewsCache;
use App\Models\RiskScore;
use App\Models\WeatherSnapshot;
use Illuminate\Console\Command;

class CalculateRisk extends Command
{
    protected $signature = 'calculate:risk';

    protected $description = 'Menghitung risk score setiap negara';

    public function handle()
    {
        $this->info('Menghitung Risk Score...');

        $scores = [];

        foreach (Country::all() as $country) {

            $weather = WeatherSnapshot::where('country_id', $country->id)
                ->latest()
                ->first();

            $economic = EconomicIndicator::where('country_id', $country->id)
                ->latest()
                ->first();

            $currency = ExchangeRate::where('country_id', $country->id)
                ->latest()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | WEATHER
            |--------------------------------------------------------------------------
            */

            $weatherScore = match ($weather->storm_risk ?? 'low') {
                'high' => 100,
                'medium' => 60,
                default => 20,
            };

            /*
            |--------------------------------------------------------------------------
            | INFLATION
            |--------------------------------------------------------------------------
            */

            $inflation = $economic->inflation ?? 0;

            $inflationScore = min($inflation * 10, 100);

            /*
            |--------------------------------------------------------------------------
            | NEWS
            |--------------------------------------------------------------------------
            */

            $negativeNews = NewsCache::where('country_id', $country->id)
                ->where('sentiment', 'Negative')
                ->count();

            $positiveNews = NewsCache::where('country_id', $country->id)
                ->where('sentiment', 'Positive')
                ->count();

            $newsScore = ($negativeNews * 15) - ($positiveNews * 5);

            if ($newsScore < 0) {
                $newsScore = 0;
            }

            if ($newsScore > 100) {
                $newsScore = 100;
            }

            /*
            |--------------------------------------------------------------------------
            | CURRENCY
            |--------------------------------------------------------------------------
            */

            $currencyScore = $currency ? 50 : 10;

            /*
            |--------------------------------------------------------------------------
            | TOTAL SCORE
            |--------------------------------------------------------------------------
            */

            $total = round(

                ($weatherScore * 0.35) +
                ($inflationScore * 0.25) +
                ($newsScore * 0.25) +
                ($currencyScore * 0.15),

                2

            );

            $scores[] = [

                'country_id' => $country->id,

                'country_name' => $country->name,

                'weather_score' => $weatherScore,

                'inflation_score' => $inflationScore,

                'news_score' => $newsScore,

                'currency_score' => $currencyScore,

                'total_score' => $total

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | SORT BERDASARKAN SCORE
        |--------------------------------------------------------------------------
        */

        usort($scores, function ($a, $b) {
            return $b['total_score'] <=> $a['total_score'];
        });

        $totalCountries = count($scores);

        $highLimit = ceil($totalCountries * 0.10);      // Top 10%
        $mediumLimit = ceil($totalCountries * 0.40);    // Top 40%

        foreach ($scores as $index => $score) {

            if ($index < $highLimit) {

                $level = "High";

            } elseif ($index < $mediumLimit) {

                $level = "Medium";

            } else {

                $level = "Low";
            }

            RiskScore::updateOrCreate(

                [
                    'country_id' => $score['country_id']
                ],

                [

                    'weather_score' => $score['weather_score'],

                    'inflation_score' => $score['inflation_score'],

                    'news_score' => $score['news_score'],

                    'currency_score' => $score['currency_score'],

                    'total_score' => $score['total_score'],

                    'risk_level' => $level

                ]

            );

            $this->line(
                $score['country_name'] .
                " ✔ " .
                $score['total_score'] .
                " (" . $level . ")"
            );
        }

        $this->newLine();

        $this->info("==============================");
        $this->info("Risk Score berhasil dihitung");
        $this->info("High   : {$highLimit}");
        $this->info("Medium : " . ($mediumLimit - $highLimit));
        $this->info("Low    : " . ($totalCountries - $mediumLimit));
        $this->info("==============================");

        return Command::SUCCESS;
    }
}