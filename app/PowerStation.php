<?php

namespace App;

class PowerStation
{
    public $rawData;

    public function __construct(array $rawData)
    {
        $this->rawData = $rawData;
    }

    public function id()
    {
        return $this->rawData['powerstation_id'];
    }

    public function owner()
    {
        $userMap = array_flip(
            config('goodwe.users')
        );

        return $userMap[$this->id()];
    }

    public function nowGenerating()
    {
        return $this->rawData['pac'];
    }

    public function energyProducedToday()
    {
        return $this->rawData['eday'];
    }

    public function isWorking()
    {
        return $this->nowGenerating() > 50;
    }
}