<?php

namespace App\Http\Controllers;

use App\Models\RiskScore;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function pdf()
    {
        $riskScores = RiskScore::with('country')
            ->orderByDesc('total_score')
            ->get();

        $pdf = Pdf::loadView('exports.risk-pdf', [
            'riskScores' => $riskScores
        ]);

        return $pdf->download('Global_Supply_Chain_Risk_Report.pdf');
    }
}