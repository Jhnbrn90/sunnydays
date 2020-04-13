<?php

namespace App\Http\Controllers;

use App\Services\YahooWeatherProvider;

class WeatherData
{
    public function __invoke(YahooWeatherProvider $yahoo)
    {
        return $yahoo->condition();
    }
}
