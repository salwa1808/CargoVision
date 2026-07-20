<?php

namespace App\Http\Controllers;

use App\Models\RiskScore;

class RiskScoreController extends Controller
{
    public function index()
    {
        return RiskScore::with('country')
            ->orderByDesc('total_score')
            ->get();
    }
}