<?php

namespace App\Console\Commands;

use App\Contracts\RetrieverInterface;
use App\Mail\CurrentYieldMail;
use App\DTO\PowerStation as PowerStationDTO;
use App\Services\YahooWeatherProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailCurrentYield extends Command
{
    protected $signature = 'sunnydays:mail-snapshot';

    protected $description = 'Send a daily mail reporting the currently generated power';

    private $retriever;

    private $yahoo;

    public function __construct(RetrieverInterface $retriever, YahooWeatherProvider $yahoo)
    {
        parent::__construct();

        $this->retriever = $retriever;
        $this->yahoo = $yahoo;
    }

    public function handle(): void
    {
        $weather = $this->yahoo->condition();
        $powerStations = collect($this->retriever->getPowerStations());

        $currentStats = $powerStations->flatMap(function (PowerStationDTO $powerStation) {
            return [$powerStation->getModel()->name => $powerStation->nowGenerating()];
        });

        Mail::to(config('app.mail'))->send(new CurrentYieldMail($currentStats, $weather));

        $this->info('Done');
    }
}
