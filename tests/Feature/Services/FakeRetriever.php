<?php

namespace Tests\Feature\Services;

use App\Contracts\RetrieverInterface;
use App\DTO\PowerStation as PowerStationDTO;
use App\DTO\PowerStationDTOCollection;

class FakeRetriever implements RetrieverInterface
{
    private PowerStationDTOCollection $powerStations;

    public function __construct()
    {
        $this->powerStations = new PowerStationDTOCollection([]);
    }

    public function getPowerStations(): PowerStationDTOCollection
    {
        return $this->powerStations;
    }

    public function returnPowerStation(PowerStationDTO $powerStation): self
    {
        $this->powerStations->push($powerStation);

        return $this;
    }
}