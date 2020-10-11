<?php

namespace App\Console\Commands;

use App\Services\GoodWeApi;
use App\Mail\HeartbeatMail;
use App\Models\PowerStation;
use App\Services\YahooWeatherProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailCurrentYield extends Command
{
    protected $signature = 'sunnydays:mail-snapshot';

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
        $this->call('sunnydays:browsershot');

        $weather = $this->yahoo->condition();
        $powerStations = $this->retriever->getPowerStations();

        $currentStats = $powerStations->flatMap(function (PowerStation $powerStation) {
            return [$powerStation->owner() => $powerStation->nowGenerating()];
        });

        Mail::to(config('app.mail'))->send(new HeartbeatMail($currentStats, $weather));

        $this->info('Done');
    }
}
