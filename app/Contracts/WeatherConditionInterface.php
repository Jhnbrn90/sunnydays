<?php

namespace App\Contracts;

interface WeatherConditionInterface
{
    public function getTemperature(): float;
    public function getIconUrl(): string;
}