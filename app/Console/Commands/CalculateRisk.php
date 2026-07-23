<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Services\RiskScoringService;
use Illuminate\Console\Command;

class CalculateRisk extends Command
{
    protected $signature = 'calculate:risk';
    protected $description = 'Menghitung risk score setiap negara dengan weighted risk model';

    public function handle(RiskScoringService $scoring): int
    {
        $bar = $this->output->createProgressBar(Country::count());
        Country::orderBy('id')->each(function (Country $country) use ($scoring, $bar) {
            $scoring->calculate($country);
            $bar->advance();
        });
        $bar->finish();
        $this->newLine();
        $this->info('Bobot risiko: Weather 30%, Inflation 20%, News 40%, Currency 10%.');
        return self::SUCCESS;
    }
}
