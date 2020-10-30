<?php

namespace App\Console\Commands;

use App\Contracts\RetrieverInterface;
use App\DTO\PowerStation as PowerStationDTO;
use Illuminate\Console\Command;

class StoreCurrentYield extends Command
{
    protected $signature = 'sunnydays:store-snapshot';

    protected $description = 'Get the current power values from Goodwe';

    private $retriever;

    public function __construct(RetrieverInterface $retriever)
    {
        parent::__construct();

        $this->retriever = $retriever;
    }

    public function handle(): void
    {
        $powerStations = collect($this->retriever->getPowerStations());

        $activePowerStations = $powerStations->filter(function (PowerStationDTO $powerStation) {
            return $powerStation->isWorking();
        });

        $activePowerStations->each(function (PowerStationDTO $powerStation) {
            $powerStation->getModel()->storeCurrentYield($powerStation->nowGenerating());
        });
    }
}