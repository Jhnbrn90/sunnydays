<?php

namespace App\Services;

class PowerStation
{
    public static function getOwner(string $powerStationId)
    {
        $lookup = array_flip(config('services.goodwe'));

        return $lookup[$powerStationId];
    }
}