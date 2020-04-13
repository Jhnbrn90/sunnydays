<?php

namespace App\Http\Controllers;

use App\DataRetriever;
use App\Services\PowerStation;
use App\YahooWeather;

class DailyGeneratedPower extends Controller
{
    public function __invoke(DataRetriever $retriever)
    {
        $powerStations = collect($retriever->getAllPowerStationData());

        return $powerStations->flatMap(function ($powerStation) {
            $label = PowerStation::getOwner($powerStation['powerstation_id']);
            $energy = (int) $powerStation['eday'] * 1000;

            return [$label => ['energy_today' => $energy]];
        });
    }
}