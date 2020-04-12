<?php

namespace App\Http\Controllers;

use App\DataRetriever;
use App\Services\PowerStation;
use App\YahooWeather;

class CurrentlyGeneratedPower extends Controller
{
    public function __invoke(DataRetriever $retriever)
    {
        $weather = (new YahooWeather())->currentCondition();
        $powerStations = collect($retriever->getAllPowerStationData());

        return $powerStations->flatMap(function ($powerStation) use ($weather) {
            $label = PowerStation::getOwner($powerStation->powerstation_id);

            return [
                $label => [
                    'power' => $powerStation->pac,
                    'temperature' => $weather->temperature,
                    'condition'=> $weather->text,
                    'code' => $weather->code,
                ]
            ];
        });
    }
}