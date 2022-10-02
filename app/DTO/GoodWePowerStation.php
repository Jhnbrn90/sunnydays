<?php

namespace App\DTO;

use App\Models\PowerStation as PowerStationModel;

class GoodWePowerStation
{
    public array $rawData;

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
        return $this->getModel()->name;
    }

    /**
     * Get the accent color for a specific Power Station.
     *
     * @return string
     */
    public function ownerColor(): string
    {
        return $this->getModel()->line_color;
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
     * @return float
     */
    public function energyProducedToday(): float
    {
        return $this->rawData['eday'];
    }

    /**
     * Returns the energy produced this month in kWh.
     *
     * @return float
     */
    public function energyProducedThisMonth(): float
    {
        return $this->rawData['emonth'];
    }

    /**
     * Returns the total produced energy in kWh.
     *
     * @return int
     */
    public function energyProducedTotal(): int
    {
        return $this->rawData['etotal'];
    }

    /**
     * Determine if the power station is currently working,
     * by comparing the currently generated energy to a threshold.
     *
     * @return bool
     */
    public function isWorking(): bool
    {
        $thresholdInWatts = config('sunnydays.power_stations.working_threshold');

        return $this->nowGenerating() > $thresholdInWatts;
    }

    /**
     * Return the corresponding Power Station Eloquent model.
     *
     * @return PowerStationModel|null
     */
    public function getModel(): ?PowerStationModel
    {
        return PowerStationModel::firstWhere('goodwe_id', $this->id());
    }

    /**
     * Get the calculated average yield of energy produced per day,
     * via the corresponding model.
     *
     * @return float|null
     */
    public function dailyProductionAverage(): ?float
    {
        return optional($this->getModel())->daily_average;
    }

    /**
     * Determine if this Power Station is tracked, meaning
     * it has a corresponding model in the database.
     *
     * @return bool
     */
    public function shouldRetrieveData(): bool
    {
        return $this->getModel() !== null;
    }
}
