<?php

namespace App\Console\Commands;

use App\GoodWeApi;
use App\Mail\HeartbeatMail;
use App\PowerStation;
use App\Services\YahooWeatherProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyMail extends Command
{
    protected $signature = 'sunnydays:daily-mail';
    protected $description = 'Send a daily mail reporting the currently generated power';

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

        $currentStats = $powerStations->flatMap(function (PowerStation $powerStation) {
            return [$powerStation->owner() => $powerStation->nowGenerating()];
        });

        Mail::to(config('app.mail'))->send(new HeartbeatMail($currentStats, $weather));

        $this->info('Done');
    }
}
