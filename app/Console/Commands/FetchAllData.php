<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class FetchAllData extends Command
{
    protected $signature = 'fetch:all';

    protected $description = 'Fetch all external data';

    public function handle()
    {
        $this->info('==============================');
        $this->info('GLOBAL SUPPLY CHAIN UPDATE');
        $this->info('==============================');

        $commands = [

            'fetch:countries',

            'fetch:economic',

            'fetch:weather',

            'fetch:exchange',

            'fetch:news',

            'calculate:risk',

            'risk:history',

        ];

        foreach ($commands as $command) {

            try{

                $this->info("Running : {$command}");

                Artisan::call($command);

                $this->line(Artisan::output());

            }

            catch(Throwable $e){

                $this->warn("Skip {$command}");

            }

        }

        $this->info('==============================');
        $this->info('UPDATE FINISHED');
        $this->info('==============================');

        return self::SUCCESS;
    }
}