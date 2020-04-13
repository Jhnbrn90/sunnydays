<?php

namespace App\Http\Controllers;

use App\DataRetriever;
use App\Services\PowerStation;
use App\Services\YahooWeatherProvider;

class CurrentlyGeneratedPower extends Controller
{
    public function __invoke(DataRetriever $retriever, YahooWeatherProvider $yahoo)
    {
        $weather = $yahoo->condition();
        $powerStations = collect($retriever->getAllPowerStationData());

        return $powerStations->flatMap(function ($powerStation) use ($weather) {
            $label = PowerStation::getOwner($powerStation['powerstation_id']);

            return [
                $label => array_merge(['power' => $powerStation['pac']], $weather)
            ];
        });
    }
}