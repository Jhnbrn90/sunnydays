<?php

namespace App\Contracts;

use App\DTO\GoodWePowerStationCollection;

interface RetrieverInterface
{
    public function getPowerStations(): GoodWePowerStationCollection;
}