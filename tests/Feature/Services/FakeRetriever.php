<?php

namespace Tests\Feature\Services;

use App\Contracts\RetrieverInterface;
use App\DTO\GoodWePowerStation as PowerStationDTO;
use App\DTO\GoodWePowerStationCollection;

class FakeRetriever implements RetrieverInterface
{
    private GoodWePowerStationCollection $powerStations;

    public function __construct()
    {
        $this->powerStations = new GoodWePowerStationCollection([]);
    }

    public function getPowerStations(): GoodWePowerStationCollection
    {
        return $this->powerStations;
    }

    public function returnPowerStation(PowerStationDTO $powerStation): self
    {
        $this->powerStations->push($powerStation);

        return $this;
    }
}