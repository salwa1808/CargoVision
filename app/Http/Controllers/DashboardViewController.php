<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;
use App\Models\User;
use App\Models\Watchlist;

class DashboardViewController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function country($id)
    {
        $country = Country::findOrFail($id);

        $risk = RiskScore::where('country_id', $id)->first();
        
        $user = auth()->user();
        $isWatchlisted = false;
        if ($user) {
            $isWatchlisted = Watchlist::where('user_id', $user->id)
                ->where('country_id', $id)
                ->exists();
        }

        return view('country', compact('country', 'risk', 'isWatchlisted'));
    }

    public function countries()
    {
        $countries = Country::with('riskScores')->orderBy('name')->get();
        
        $user = auth()->user();
        $watchlistIds = [];
        if ($user) {
            $watchlistIds = Watchlist::where('user_id', $user->id)
                ->pluck('country_id')
                ->toArray();
        }

        return view('countries', compact('countries', 'watchlistIds'));
    }

    public function economy()
    {
        $countries = Country::with(['economicIndicators' => function($q) {
            $q->orderByDesc('year');
        }])->orderBy('name')->get();
        
        $user = auth()->user();
        $watchlistIds = [];
        if ($user) {
            $watchlistIds = Watchlist::where('user_id', $user->id)
                ->pluck('country_id')
                ->toArray();
        }

        return view('economy', compact('countries', 'watchlistIds'));
    }

    public function currency()
    {
        $countries = Country::with(['exchangeRates' => function($q) {
            $q->latest('id');
        }])->orderBy('name')->get();
        
        $user = auth()->user();
        $watchlistIds = [];
        if ($user) {
            $watchlistIds = Watchlist::where('user_id', $user->id)
                ->pluck('country_id')
                ->toArray();
        }

        return view('currency', compact('countries', 'watchlistIds'));
    }
}