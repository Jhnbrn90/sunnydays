<?php

namespace App\Http\Controllers;

use App\YahooWeather;

class WeatherData extends Controller
{
    public function __invoke()
    {
        $yahoo = new YahooWeather();
        
        return [
            'text'          => $yahoo->currentCondition()->text,
            'temperature'   => $yahoo->currentCondition()->temperature,
            'code'          => $yahoo->currentCondition()->code,
        ];
    }
}
