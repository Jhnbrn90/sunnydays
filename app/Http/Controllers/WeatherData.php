<?php

namespace App\Http\Controllers;

use App\Services\YahooWeatherProvider;

class WeatherData extends Controller
{
    public function __invoke(YahooWeatherProvider $yahoo)
    {
        return $yahoo->condition();
    }
}
