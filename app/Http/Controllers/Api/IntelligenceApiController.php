<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ExchangeRate;
use App\Models\NewsCache;
use App\Models\RiskScore;
use Illuminate\Http\Request;

class IntelligenceApiController extends Controller
{
    public function countries()
    {
        return Country::orderBy('name')->get();
    }

    public function risk()
    {
        return RiskScore::with('country')->orderByDesc('total_score')->get();
    }

    public function news(Request $request)
    {
        return NewsCache::with('country')
            ->when($request->country_id, fn ($query, $countryId) => $query->where('country_id', $countryId))
            ->latest()
            ->limit(100)
            ->get();
    }

    public function currency(Request $request)
    {
        return ExchangeRate::with('country')
            ->when($request->country_id, fn ($query, $countryId) => $query->where('country_id', $countryId))
            ->latest()
            ->limit(500)
            ->get();
    }
}
