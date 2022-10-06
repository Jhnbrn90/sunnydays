<?php

namespace App\DTO;

use App\DTO\Traits\ToArray;
use Illuminate\Support\Collection;

class GoodWePowerStationCollection extends Collection
{
    use ToArray;

    public function registered(): self
    {
        return $this->filter(function (GoodWePowerStation $powerStation) {
            return $powerStation->shouldRetrieveData();
        });
    }

    public function working(): self
    {
        return $this->filter(function (GoodWePowerStation $powerStation) {
            return $powerStation->isWorking();
        });
    }
}