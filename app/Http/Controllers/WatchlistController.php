<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $watchlistItems = Watchlist::where('user_id', $user->id)
            ->with([
                'country.riskScores',
                'country.weatherSnapshots' => function ($q) {
                    $q->latest('id');
                },
                'country.economicIndicators' => function ($q) {
                    $q->orderByDesc('year');
                },
                'country.exchangeRates' => function ($q) {
                    $q->latest('id');
                }
            ])
            ->get();

        $watchlistedCountryIds = $watchlistItems->pluck('country_id')->toArray();
        $availableCountries = Country::whereNotIn('id', $watchlistedCountryIds)
            ->orderBy('name')
            ->get();

        return view('watchlist', compact('watchlistItems', 'availableCountries'));
    }

    public function toggle(Request $request, $countryId)
    {
        $user = auth()->user();

        $country = Country::findOrFail($countryId);

        $watchlist = Watchlist::where('user_id', $user->id)
            ->where('country_id', $country->id)
            ->first();

        if ($watchlist) {
            $watchlist->delete();
            $watchlisted = false;
        } else {
            Watchlist::create([
                'user_id' => $user->id,
                'country_id' => $country->id,
            ]);
            $watchlisted = true;
        }

        return response()->json([
            'success' => true,
            'watchlisted' => $watchlisted,
            'message' => $watchlisted ? 'Country added to watchlist.' : 'Country removed from watchlist.'
        ]);
    }
}
