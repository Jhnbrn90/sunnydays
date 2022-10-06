<?php

namespace App\DTO;

use App\DTO\Traits\ToArray;
use App\Models\PowerStation;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class SmartMeterPowerStationCollection extends Collection
{
    use ToArray;
    
    public static function fromEloquentCollection(EloquentCollection $collection): self
    {
        $powerStations = $collection->map(function (PowerStation $model) {
            return new SmartMeterPowerStation($model);
        });
        
        return new self($powerStations);
    }
}