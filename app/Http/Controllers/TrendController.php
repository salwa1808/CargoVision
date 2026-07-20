<?php

namespace App\Http\Controllers;

use App\Models\RiskScoreHistory;

class TrendController extends Controller
{
    public function index($country)
    {
        $history = RiskScoreHistory::where('country_id', $country)
            ->orderBy('created_at')
            ->get([
                'total_score',
                'risk_level',
                'created_at'
            ]);

        return response()->json($history);
    }
}