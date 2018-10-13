<?php

namespace App\Http\Controllers;

use App\Powerlog;
use Carbon\Carbon;
use App\DataRetriever;
use App\DailyProductionLog;

class ApiController extends Controller
{

    public $users;

    public function __construct() {
        $this->users = array_keys(\Config::get('services.goodwe'));
    }

    public function getPowerStation($powerstation)
    {
        $retriever = new DataRetriever($powerstation);

        return $retriever->getPowerStationData();
    }

    public function goodwe($id)
    {
        $retriever = new DataRetriever($id);
        if (request()->wantsJson()) {
            return response()->json($retriever->getPowerStationData());
        }

        return $retriever->getPowerStationData();
    }

    public function hourly()
    {
        // get Yahoo data
        $url = 'https://query.yahooapis.com/v1/public/yql?q=select%20item.condition%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text=%27Hoofddorp%27)%20and%20u=%27c%27&format=json';
        $response = json_decode(file_get_contents($url), true);
        $yahoo = $response['query']['results']['channel']['item']['condition'];

        foreach ($this->users as $user) {
            $goodweIds[$user] = \Config::get('services.goodwe.' . $user);
        }

        foreach ($goodweIds as $user => $goodweId) {
            $data = $this->getPowerStation($goodweId);

            $entry[$user]['power'] = $data->kpi->pac;
            $entry[$user]['temperature'] = $yahoo['temp'];
            $entry[$user]['condition'] = $yahoo['text'];
            $entry[$user]['code'] = $yahoo['code'];
        }

        return $entry;
    }

    public function daily()
    {
        foreach ($this->users as $user) {
            $goodweIds[$user] = \Config::get('services.goodwe.' . $user);
        }

        foreach ($goodweIds as $user => $goodweId) {
            $data = $this->getPowerStation($goodweId);

            $entry[$user]['energy_today'] = intval($data->inverter[0]->eday) * 1000;
        }

        return $entry;
    }

    public function production()
    {
        foreach ($this->users as $user) {
            $result = [];
            $weeklyEntries[$user] = DailyProductionLog::where('user', $user)->thisWeek()->orderBy('created_at', 'ASC')->get();

            foreach ($weeklyEntries[$user] as $entry) {
                $result[(string)$entry->created_at->format('m-d-Y')] = $entry->total_production / 1000;
            }

            $collection[$user] = $result;
        }

        return collect($collection);
    }

    public function dailyGraph($date)
    {
        $start = Carbon::parse($date)->startOfDay();

        foreach ($this->users as $user) {
            $log = [];
            $powerlogs = Powerlog::where('user', $user)->whereDate('created_at', $start)->get();

            foreach ($powerlogs as $powerlog) {
                $log[(string)$powerlog->created_at->format('H:i')] = [
                    'power' => $powerlog->current_power,
                    'weather_condition' => $powerlog->weather_condition,
                    'temperature' => $powerlog->temperature
                ];
            }

            $collection[$user] = $log;
        }

        return collect($collection);
    }

    public function getAverage()
    {
        foreach($this->users as $user) {
            $production = DailyProductionLog::where('user', $user)->pluck('total_production');
            $result[$user] = number_format(array_sum($production->toArray())/$production->count()/1000, 1);
        }

        return collect($result);
    }
}
