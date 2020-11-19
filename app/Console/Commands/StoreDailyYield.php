<?php

namespace App\Console\Commands;

use App\Contracts\RetrieverInterface;
use App\DTO\PowerStation as PowerStationDTO;
use Carbon\Carbon;
use Illuminate\Console\Command;

class StoreDailyYield extends Command
{
    protected $signature = 'sunnydays:store-yield';

    protected $description = 'Store the daily total generated energy';

    private $retriever;

    public function __construct(RetrieverInterface $retriever)
    {
        parent::__construct();

        $this->retriever = $retriever;
    }

    public function handle(): void
    {
        $powerStations = $this->retriever->getPowerStations();

        $powerStations->registered()->each(function (PowerStationDTO $powerStation) {
            $log = $powerStation
                ->getModel()
                ->dailyProductionLogs()
                ->firstOrNew(['created_at' => Carbon::today()]);

            $log->total_production = $powerStation->energyProducedToday() * 1000;

            $log->save();

            dd($log);
        });

        $this->info('Done');
    }
}
