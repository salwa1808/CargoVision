<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();

        $compareIds = $request->input('countries', []);
        $compareIds = array_slice(array_filter($compareIds, 'is_numeric'), 0, 3);

        $compareCountries = collect();
        if (!empty($compareIds)) {
            $compareCountries = Country::whereIn('id', $compareIds)
                ->with([
                    'riskScores',
                    'weatherSnapshots' => function ($q) {
                        $q->latest('id');
                    },
                    'economicIndicators' => function ($q) {
                        $q->orderByDesc('year');
                    },
                    'exchangeRates' => function ($q) {
                        $q->latest('id');
                    }
                ])
                ->get()
                // Sort according to the order of requested IDs to keep layout consistent with selection
                ->sortBy(function ($country) use ($compareIds) {
                    return array_search($country->id, $compareIds);
                })
                ->values();
        }

        return view('compare', compact('countries', 'compareCountries', 'compareIds'));
    }
}
