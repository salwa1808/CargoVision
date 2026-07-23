<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardViewController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\PortController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GlobalMapController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\AdminPortController;
use App\Http\Controllers\AdminDataController;
use App\Http\Controllers\ArticleController;

// Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);
});

// Logout Route (Needs auth)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Main Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardViewController::class, 'index']);

    Route::get('/countries', [DashboardViewController::class, 'countries'])->name('countries');
    Route::get('/economy', [DashboardViewController::class, 'economy'])->name('economy');
    Route::get('/currency', [DashboardViewController::class, 'currency'])->name('currency');

    Route::get('/country/{id}', [DashboardViewController::class, 'country']);

    Route::get('/export/pdf', [ExportController::class, 'pdf']);

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    Route::get('/ports', [PortController::class, 'index'])->name('ports');
    Route::resource('shipments', ShipmentController::class)->only(['index', 'show']);

    Route::get('/news', [NewsController::class, 'index'])->name('news');

    Route::get('/weather', [WeatherController::class, 'index'])->name('weather');

    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/toggle/{country}', [WatchlistController::class, 'toggle'])->name('watchlist.toggle');

    Route::get('/compare', [CompareController::class, 'index'])->name('compare');
    Route::get('/map', [GlobalMapController::class, 'index'])->name('map');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Admin Panel Routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::resource('ports', AdminPortController::class)->only(['index','store','update','destroy'])->names('admin.ports');
        Route::resource('articles', ArticleController::class)->except(['show'])->names('admin.articles');
        Route::post('data/sync', [AdminDataController::class, 'sync'])->name('admin.data.sync');
        Route::post('risk/recalculate', [AdminDataController::class, 'recalculateRisk'])->name('admin.risk.recalculate');
        Route::post('weather/refresh/{id}', [WeatherController::class, 'refreshWeather'])->name('admin.weather.refresh');
    });
});
