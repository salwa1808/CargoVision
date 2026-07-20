<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        return Country::with('riskScores')->orderBy('name')->get();
    }

    public function show($id)
    {
        return Country::with([
            'economicIndicators',
            'weatherSnapshots',
            'exchangeRates',
            'riskScores'
        ])->findOrFail($id);
    }

    public function detail($id)
    {
        $country = Country::with('riskScores')->findOrFail($id);

        $risk = $country->riskScores->first();

        return view('country', compact('country','risk'));
    }
}