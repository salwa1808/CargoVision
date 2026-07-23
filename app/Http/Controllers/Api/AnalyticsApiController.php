<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\EconomicIndicator;
use App\Models\ExchangeRate;
use App\Models\RiskScore;
use App\Models\RiskScoreHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsApiController extends Controller
{
    public function index(Request $request)
    {
        $countryId = $request->integer('country_id')
            ?: RiskScore::orderByDesc('total_score')->value('country_id')
            ?: Country::value('id');

        $riskTrend = RiskScoreHistory::where('country_id', $countryId)
            ->oldest()
            ->get(['created_at', 'total_score']);

        return response()->json([

            'region' => Country::select(
                    'region',
                    DB::raw('COUNT(*) as total')
                )
                ->groupBy('region')
                ->orderByDesc('total')
                ->get(),

            'highest' => RiskScore::with('country')
                ->orderByDesc('total_score')
                ->take(5)
                ->get(),

            'lowest' => RiskScore::with('country')
                ->orderBy('total_score')
                ->take(5)
                ->get(),

            'risk_factors' => [
                'weather' => round((float) RiskScore::avg('weather_score'), 2),
                'inflation' => round((float) RiskScore::avg('inflation_score'), 2),
                'news' => round((float) RiskScore::avg('news_score'), 2),
                'currency' => round((float) RiskScore::avg('currency_score'), 2),
            ],

            'selected_country' => Country::find($countryId),

            'economic_trend' => EconomicIndicator::where('country_id', $countryId)
                ->orderBy('year')
                ->get(['year', 'gdp', 'inflation']),

            'currency_trend' => ExchangeRate::where('country_id', $countryId)
                ->oldest()
                ->get(['created_at', 'exchange_rate', 'currency_code']),

            'risk_trend' => $riskTrend,

        ]);
    }
}
