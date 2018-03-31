<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{
    public function hourly()
    {
        // get Yahoo data
        $url = 'https://query.yahooapis.com/v1/public/yql?q=select%20item.condition%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text=%27Hoofddorp%27)%20and%20u=%27c%27&format=json';
        $response = json_decode(file_get_contents($url), true);
        $yahoo = $response['query']['results']['channel']['item']['condition'];

        // get Goodwe data
        $goodweId = \Config::get('services.goodwe.id');
        $url = 'http://www.goodwe-power.com/Mobile/GetMyPowerStationById?stationID=' . $goodweId;
        $response = json_decode(file_get_contents($url), true);
        $power = $response['curpower'];

        $entry['power'] = substr($power, 0, -2) * 1000;
        $entry['temperature'] = $yahoo['temp'];
        $entry['condition'] = $yahoo['text'];
        $entry['code'] = $yahoo['code'];

        return $entry;
    }
}
