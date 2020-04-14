<?php

namespace App\Http\Controllers;

use App\GoodWeApi;
use App\PowerStation;

class ShowPowerStations
{
    public function __invoke(GoodWeApi $retriever)
    {
        $powerStations = $retriever->getPowerStations();

        return $powerStations->flatMap(function (PowerStation $powerStation) {
            return [$powerStation->owner() => $powerStation->rawData];
        });
    }
}