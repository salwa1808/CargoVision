<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class GlobalMapController extends Controller
{
    public function index()
    {
        // Load all countries with their relations
        $countries = Country::with([
            'riskScores',
            'weatherSnapshots' => function ($query) {
                $query->latest();
            },
            'economicIndicators' => function ($query) {
                $query->orderByDesc('year');
            },
            'exchangeRates' => function ($query) {
                $query->latest();
            }
        ])->orderBy('name')->get();

        // Map them to a structured array for JSON injection into the frontend
        $countriesData = $countries->map(function ($country) {
            $risk = $country->riskScores->first();
            $weather = $country->weatherSnapshots->first();
            $economic = $country->economicIndicators->first();
            $exchange = $country->exchangeRates->first();

            return [
                'id' => $country->id,
                'name' => $country->name,
                'official_name' => $country->official_name ?? $country->name,
                'cca2' => $country->cca2,
                'cca3' => $country->cca3,
                'latitude' => $country->latitude,
                'longitude' => $country->longitude,
                'currency_code' => $country->currency_code,
                'currency_name' => $country->currency_name,
                'currency_symbol' => $country->currency_symbol,
                'flag_png' => $country->flag_png,
                'flag_svg' => $country->flag_svg,
                'risk' => $risk ? [
                    'weather_score' => $risk->weather_score,
                    'inflation_score' => $risk->inflation_score,
                    'news_score' => $risk->news_score,
                    'currency_score' => $risk->currency_score,
                    'total_score' => $risk->total_score,
                    'risk_level' => $risk->risk_level,
                    'updated_at' => $risk->updated_at ? $risk->updated_at->toDateTimeString() : null,
                ] : null,
                'weather' => $weather ? [
                    'temperature' => $weather->temperature,
                    'wind_speed' => $weather->wind_speed,
                    'rainfall' => $weather->rainfall,
                    'storm_risk' => $weather->storm_risk,
                ] : null,
                'economic' => $economic ? [
                    'gdp' => $economic->gdp,
                    'inflation' => $economic->inflation,
                    'population' => $economic->population,
                    'year' => $economic->year,
                ] : null,
                'exchange_rate' => $exchange ? [
                    'rate' => $exchange->exchange_rate,
                ] : null,
            ];
        });

        return view('map', compact('countries', 'countriesData'));
    }
}
