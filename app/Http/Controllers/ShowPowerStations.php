<?php

namespace App\Http\Controllers;

use App\DTO\PowerStation;
use App\DTO\PowerStationDTOCollection;
use App\Services\GoodWeApi;
use Illuminate\Support\Collection;

class ShowPowerStations
{
    public function __invoke(GoodWeApi $retriever): PowerStationDTOCollection
    {
        $powerStations = $retriever->getPowerStations();

        return $powerStations->registered();
    }
}