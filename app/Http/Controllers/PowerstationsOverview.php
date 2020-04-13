<?php

namespace App\Http\Controllers;

use App\DataRetriever;
use App\Services\PowerStation;

class PowerstationsOverview
{
    public function __invoke(DataRetriever $retriever)
    {
        $powerStations = collect($retriever->getAllPowerStationData());

        return $powerStations->flatMap(function ($powerStation) {
            $label = PowerStation::getOwner($powerStation['powerstation_id']);

            return [$label => $powerStation];
        });
    }
}