<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\RiskScore;
use Illuminate\Support\Facades\DB;

class AnalyticsApiController extends Controller
{
    public function index()
    {
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
                ->get()

        ]);
    }
}