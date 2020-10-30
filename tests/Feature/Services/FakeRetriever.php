<?php

namespace Tests\Feature\Services;

use App\Contracts\RetrieverInterface;
use App\DTO\PowerStation as PowerStationDTO;

class FakeRetriever implements RetrieverInterface
{
    private $powerStations = [];

    public function getPowerStations()
    {
        return $this->powerStations;
    }

    public function returnPowerStation(PowerStationDTO $powerStation): self
    {
        $this->powerStations[] = $powerStation;

        return $this;
    }
}