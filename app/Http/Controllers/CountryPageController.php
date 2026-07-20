<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryPageController extends Controller
{
    public function show($id)
    {
        $country = Country::with([
            'economicIndicators',
            'weatherSnapshots',
            'exchangeRates',
            'riskScores'
        ])->findOrFail($id);

        return view('country', compact('country'));
    }
}