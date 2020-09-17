<?php

namespace App\Console\Commands;

use App\Models\DailyProductionLog;
use App\Models\GoodWeApi;
use App\Models\PowerStation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class StoreDailyYield extends Command
{
    protected $signature = 'sunnydays:store-yield';

    protected $description = 'Store the daily total generated energy';

    private $retriever;

    public function __construct(GoodWeApi $retriever)
    {
        parent::__construct();

        $this->retriever = $retriever;
    }

    public function handle(): void
    {
        $powerStations = $this->retriever->getPowerStations();

        $powerStations->each(function (PowerStation $powerStation) {
            $log = DailyProductionLog::firstOrNew([
                'created_at' => Carbon::today(),
                'user' => $powerStation->owner(),
            ]);

            $log->total_production = $powerStation->energyProducedToday() * 1000;

            $log->save();
        });

        $this->info('Done');
    }
}
