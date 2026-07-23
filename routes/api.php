<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\RiskScoreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TrendController;
use App\Models\Port;
use App\Http\Controllers\Api\AnalyticsApiController;
use App\Http\Controllers\Api\IntelligenceApiController;
use App\Models\RiskScore;
use App\Http\Controllers\WeatherController;

Route::get('/countries', [IntelligenceApiController::class, 'countries']);

Route::get('/countries/{id}', [CountryController::class, 'show']);

Route::get('/risk', [IntelligenceApiController::class, 'risk']);

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/trend/{country}', [TrendController::class, 'index']);

Route::get('/ports', function () {

    return Port::with('country')
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->limit(1000)
        ->get();

});

Route::get('/vessels', function () {
    return \App\Models\Vessel::with('port')->get();
});

Route::get('/analytics', [AnalyticsApiController::class,'index']);
Route::get('/news', [IntelligenceApiController::class, 'news']);
Route::get('/currency', [IntelligenceApiController::class, 'currency']);

Route::get('/high-risk', function () {

    return RiskScore::with('country')
        ->where('risk_level', 'High')
        ->orderByDesc('updated_at')
        ->take(10)
        ->get();

});

Route::get('/weather', [WeatherController::class, 'getWeatherApi']);
