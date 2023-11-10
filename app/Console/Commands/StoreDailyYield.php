<?php

namespace App\Console\Commands;

use App\Contracts\RetrieverInterface;
use App\DTO\GoodWePowerStation as PowerStationDTO;
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

            // The total_production is set to 0 for non-existing
            // production logs.
            if (! $log->exists) {
                $log->total_production = 0;
            }

            $energyProducedToday = $powerStation->energyProducedToday() * 1000;

            // Do not update daily production when retrieved value is
            // less than currently registered value.
            if ($energyProducedToday > $log->total_production) {
                $log->total_production = $energyProducedToday;
                $log->save();
            }

        });

        $this->info('Done');
    }
}
