<?php

namespace App\Http\Controllers;

class ApiController extends Controller
{
    public function goodwe()
    {
        $goodweId = \Config::get('services.goodwe.JL');
        $url = 'http://www.goodwe-power.com/Mobile/GetMyPowerStationById?stationID=' . $goodweId;
        return json_decode(file_get_contents($url), true);
    }

    public function hourly()
    {
        // get Yahoo data
        $url = 'https://query.yahooapis.com/v1/public/yql?q=select%20item.condition%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text=%27Hoofddorp%27)%20and%20u=%27c%27&format=json';
        $response = json_decode(file_get_contents($url), true);
        $yahoo = $response['query']['results']['channel']['item']['condition'];

        // get Goodwe data
        $users = ['JL', 'MB'];

        foreach ($users as $user) {
            $goodweIds[$user] = \Config::get('services.goodwe.' . $user);
        }

        foreach ($goodweIds as $user => $goodweId) {
            $url = 'http://www.goodwe-power.com/Mobile/GetMyPowerStationById?stationID=' . $goodweId;
            $response = json_decode(file_get_contents($url), true);
            $entry[$user]['power'] = substr($response['curpower'], 0, -2) * 1000;
            $entry[$user]['temperature'] = $yahoo['temp'];
            $entry[$user]['condition'] = $yahoo['text'];
            $entry[$user]['code'] = $yahoo['code'];
        }

        return $entry;
    }

    public function daily()
    {
        $goodweId = \Config::get('services.goodwe.JL');
        $url = 'http://www.goodwe-power.com/Mobile/GetMyPowerStationById?stationID=' . $goodweId;
        $response = json_decode(file_get_contents($url), true);

        $entry['energy_today'] = substr($response['eday'], 0, -3) * 1000;

        return $entry;
    }
}
