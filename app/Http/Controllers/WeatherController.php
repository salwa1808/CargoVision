<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\WeatherSnapshot;
use App\Services\RiskScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class WeatherController extends Controller
{
    /**
     * Show the weather monitoring page.
     */
    public function index()
    {
        return view('weather');
    }

    /**
     * Get all countries with their latest weather snapshots and risk levels.
     */
    public function getWeatherApi(Request $request)
    {
        $countries = Country::with(['weatherSnapshots' => function ($query) {
            $query->latest();
        }, 'riskScores'])->orderBy('name')->get();

        // Map data to a simple response format
        $data = $countries->map(function ($country) {
            $latestWeather = $country->weatherSnapshots->first();
            $risk = $country->riskScores->first();

            return [
                'id' => $country->id,
                'name' => $country->name,
                'cca2' => $country->cca2,
                'flag_png' => $country->flag_png,
                'latitude' => $country->latitude,
                'longitude' => $country->longitude,
                'region' => $country->region,
                'temperature' => $latestWeather ? $latestWeather->temperature : null,
                'wind_speed' => $latestWeather ? $latestWeather->wind_speed : null,
                'rainfall' => $latestWeather ? $latestWeather->rainfall : 0,
                'storm_risk' => $latestWeather ? $latestWeather->storm_risk : 'low',
                'weather_score' => $risk ? $risk->weather_score : 10,
                'risk_level' => $risk ? $risk->risk_level : 'Low',
                'total_score' => $risk ? $risk->total_score : 0,
                'updated_at' => $latestWeather ? $latestWeather->updated_at->toIso8601String() : null,
            ];
        });

        return response()->json($data);
    }

    /**
     * Fetch real-time weather from Open-Meteo, save it, and recalculate risk score.
     */
    public function refreshWeather($id, RiskScoringService $scoring)
    {
        $country = Country::findOrFail($id);

        if (!$country->latitude || !$country->longitude) {
            return response()->json([
                'success' => false,
                'message' => 'Country does not have latitude/longitude coordinates.'
            ], 400);
        }

        $url = "https://api.open-meteo.com/v1/forecast?latitude={$country->latitude}&longitude={$country->longitude}&current=temperature_2m,wind_speed_10m,rain";

        try {
            $response = Http::timeout(15)
                ->retry(3, 500)
                ->acceptJson()
                ->get($url);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch weather from external API.'
                ], 502);
            }

            $current = $response->json()['current'] ?? [];
            $temperature = $current['temperature_2m'] ?? null;
            $windSpeed = $current['wind_speed_10m'] ?? null;
            $rainfall = $current['rain'] ?? 0;

            // Compute storm risk using same formula
            if ($windSpeed >= 60 || $rainfall >= 50) {
                $stormRisk = 'high';
            } elseif ($windSpeed >= 30 || $rainfall >= 20) {
                $stormRisk = 'medium';
            } else {
                $stormRisk = 'low';
            }

            // Create new weather snapshot
            $snapshot = WeatherSnapshot::create([
                'country_id' => $country->id,
                'temperature' => $temperature,
                'wind_speed' => $windSpeed,
                'rainfall' => $rainfall,
                'storm_risk' => $stormRisk,
            ]);

            // Recalculate Risk Score
            $risk = $scoring->calculate($country);

            return response()->json([
                'success' => true,
                'weather' => [
                    'temperature' => $snapshot->temperature,
                    'wind_speed' => $snapshot->wind_speed,
                    'rainfall' => $snapshot->rainfall,
                    'storm_risk' => $snapshot->storm_risk,
                    'updated_at' => $snapshot->updated_at->toIso8601String(),
                ],
                'risk' => [
                    'weather_score' => $risk->weather_score,
                    'inflation_score' => $risk->inflation_score,
                    'news_score' => $risk->news_score,
                    'currency_score' => $risk->currency_score,
                    'total_score' => $risk->total_score,
                    'risk_level' => $risk->risk_level,
                ]
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

}
