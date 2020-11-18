<?php

namespace App\Contracts;

use App\DTO\PowerStationDTOCollection;

interface RetrieverInterface
{
    public function getPowerStations(): PowerStationDTOCollection;
}