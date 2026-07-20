<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RiskScore;
use App\Models\RiskScoreHistory;

class SaveRiskHistory extends Command
{
    protected $signature = 'risk:history';

    protected $description = 'Save all current risk scores into history table';

    public function handle()
    {
        $scores = RiskScore::all();

        foreach ($scores as $score) {

            RiskScoreHistory::create([

                'country_id' => $score->country_id,

                'weather_score' => $score->weather_score,

                'inflation_score' => $score->inflation_score,

                'currency_score' => $score->currency_score,

                'news_score' => $score->news_score,

                'total_score' => $score->total_score,

                'risk_level' => $score->risk_level,

            ]);

        }

        $this->info('Risk history saved successfully.');
    }
}