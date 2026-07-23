<?php

namespace App\Services;

use App\Models\Country;
use App\Models\EconomicIndicator;
use App\Models\ExchangeRate;
use App\Models\NewsCache;
use App\Models\RiskScore;
use App\Models\WeatherSnapshot;

class RiskScoringService
{
    public function calculate(Country $country): RiskScore
    {
        $weather = WeatherSnapshot::where('country_id', $country->id)->latest()->first();
        $economic = EconomicIndicator::where('country_id', $country->id)->latest('year')->first();
        $rates = ExchangeRate::where('country_id', $country->id)->latest()->take(2)->get();

        $weatherScore = match (strtolower($weather?->storm_risk ?? 'low')) {
            'high' => 100, 'medium' => 60, default => 20,
        };
        $inflationScore = min(max(abs((float) ($economic?->inflation ?? 0)) * 10, 0), 100);

        $negative = NewsCache::where('country_id', $country->id)->where('sentiment', 'Negative')->count();
        $positive = NewsCache::where('country_id', $country->id)->where('sentiment', 'Positive')->count();
        $newsScore = min(max(($negative / max($negative + $positive, 1)) * 100, 0), 100);

        $currencyScore = 10;
        if ($rates->count() >= 2 && (float) $rates[1]->exchange_rate !== 0.0) {
            $change = abs(((float) $rates[0]->exchange_rate - (float) $rates[1]->exchange_rate) / (float) $rates[1]->exchange_rate) * 100;
            $currencyScore = min($change * 10, 100);
        }

        $total = round(($weatherScore * .30) + ($inflationScore * .20) + ($newsScore * .40) + ($currencyScore * .10), 2);
        $level = $total >= 66 ? 'High' : ($total >= 33 ? 'Medium' : 'Low');

        return RiskScore::updateOrCreate(['country_id' => $country->id], [
            'weather_score' => $weatherScore,
            'inflation_score' => $inflationScore,
            'news_score' => $newsScore,
            'currency_score' => $currencyScore,
            'total_score' => $total,
            'risk_level' => $level,
        ]);
    }
}
