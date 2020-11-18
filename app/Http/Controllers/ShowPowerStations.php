<?php

namespace App\Http\Controllers;

use App\DTO\PowerStationDTOCollection;
use App\Services\GoodWeApi;

class ShowPowerStations
{
    public function __invoke(GoodWeApi $retriever): PowerStationDTOCollection
    {
        $powerStations = $retriever->getPowerStations();

        return $powerStations->registered();
    }
}