<?php

namespace App\Console\Commands;

use App\Models\DailyProductionLog;
use App\Models\GoodWeApi;
use App\Mail\StatisticsMail;
use App\Models\PowerStation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class LogDailyProducedEnergy extends Command
{
    protected $signature = 'sunnydays:daily-log';
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

        $logs = $powerStations->reduce(function ($logs, PowerStation $powerStation) {
            $logs[$powerStation->owner()] = DailyProductionLog::create([
                'total_production'  => $powerStation->energyProducedToday() * 1000,
                'user'              => $powerStation->owner(),
            ]);

            return $logs;
        }, []);

        Mail::to(config('app.mail'))->send(new StatisticsMail($logs));

        $this->info('Done');
    }
}
