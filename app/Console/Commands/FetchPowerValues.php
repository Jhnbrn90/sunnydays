<?php

namespace App\Console\Commands;

use App\Models\GoodWeApi;
use App\Models\Powerlog;
use App\Models\PowerStation;
use App\Services\YahooWeatherProvider;
use Illuminate\Console\Command;

class FetchPowerValues extends Command
{
    protected $signature = 'sunnydays:snapshot';
    protected $description = 'Get the current power values from Goodwe';

    private $retriever;
    private $yahoo;

    public function __construct(GoodWeApi $retriever, YahooWeatherProvider $yahoo)
    {
        parent::__construct();
        $this->retriever = $retriever;
        $this->yahoo = $yahoo;
    }

    public function handle(): void
    {
        $weather = $this->yahoo->condition();
        $powerStations = $this->retriever->getPowerStations();

        $powerStations->each(function (PowerStation $powerStation) use ($weather) {
           if ($powerStation->isWorking()) {
               Powerlog::create([
                   'current_power' => $powerStation->nowGenerating(),
                   'user' => $powerStation->owner(),
                   'weather_condition' => $weather['text'],
                   'temperature' => $weather['temperature'],
                   'weather_condition_code' => $weather['code']
               ]);
           }
        });

        $this->info('Done');
    }
}
