<?php

namespace App\Contracts;

Interface WeatherInterface
{
    public function fetch(): array;
}