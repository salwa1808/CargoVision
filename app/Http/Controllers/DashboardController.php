<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\RiskScore;

class DashboardController extends Controller
{
    public function index()
    {
        return [
            'total_countries' => Country::count(),

            'high_risk' => RiskScore::where('risk_level', 'High')->count(),

            'medium_risk' => RiskScore::where('risk_level', 'Medium')->count(),

            'low_risk' => RiskScore::where('risk_level', 'Low')->count(),

            'average_score' => round(RiskScore::avg('total_score'), 2),
        ];
    }
}