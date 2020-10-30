<?php

namespace App\DTO;

use App\Models\PowerStation as PowerStationModel;

class PowerStation
{
    public $rawData;

    public function __construct(array $rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * Returns the GoodWe power station ID
     *
     * @return string
     */
    public function id(): string
    {
        return $this->rawData['powerstation_id'];
    }

    /**
     * Find the owner in a list of predefined users by
     * the GoodWe power station ID
     *
     * @return string
     */
    public function owner(): string
    {
        $userMap = array_flip(
            config('goodwe.users')
        );

        return $userMap[$this->id()];
    }

    /**
     * Returns the energy currently being produced in W.
     *
     * @return int
     */
    public function nowGenerating(): int
    {
        return $this->rawData['pac'];
    }

    /**
     * Returns the energy produced today in kWh.
     *
     * @return int
     */
    public function energyProducedToday(): int
    {
        return $this->rawData['eday'];
    }

    /**
     * Determine if the power station is currently working,
     * by comparing the currently generated energy to a threshold.
     *
     * @return bool
     */
    public function isWorking(): bool
    {
        $thresholdInWatts = 50;

        return $this->nowGenerating() > $thresholdInWatts;
    }

    public function getModel(): PowerStationModel
    {
        return PowerStationModel::firstWhere('goodwe_id', $this->id());
    }
}