<?php

namespace App\Contracts;

interface PowerStationInterface
{
    /**
     * Find the owner in a list of predefined users by
     * the GoodWe power station ID
     *
     * @return string
     */    
    public function owner(): string;
    
    /**
     * Get the accent color for a specific Power Station.
     *
     * @return string
     */    
    public function ownerColor(): string;
    
    /**
     * Returns the energy currently being produced in W.
     *
     * @return int
     */    
    public function nowGenerating(): int;
    
    /**
     * Returns the energy produced today in kWh.
     *
     * @return float
     */    
    public function energyProducedToday(): float;
    
    /**
     * Returns the total produced energy in kWh.
     *
     * @return int
     */    
    public function energyProducedTotal(): int;
    
    /**
     * Get the calculated average yield of energy produced per day,
     * via the corresponding model.
     *
     * @return float|null
     */
    public function dailyProductionAverage(): ?float;
}