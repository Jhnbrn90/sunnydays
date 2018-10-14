<?php

namespace App\Http\Controllers;

use App\Powerlog;
use Carbon\Carbon;
use App\DataRetriever;
use App\DailyProductionLog;

class ApiController extends Controller
{

    public $users;
    protected $retriever;
    protected $lookup;

    public function __construct(DataRetriever $retriever) {
        $this->users = array_keys(\Config::get('services.goodwe'));
        
        $this->lookup = array_flip(\Config::get('services.goodwe'));

        $this->retriever = $retriever;
    }

    public function getPowerStation($powerstation)
    {
        return $this->retriever->getPowerStationData($powerstation);
    }

    public function goodwe($id)
    {
        return collect($this->retriever->getPowerstationData($id));
    }

    public function goodWeAll()
    {   
        $powerstations = collect($this->retriever->getAllPowerStationData()->data);

        foreach ($powerstations as $powerstation) {
            $data[$this->lookup[$powerstation->powerstation_id]] = $powerstation;
        }

        return $data;
    }

    public function getData($goodweId)
    {
        $data = $this->retriever->getPowerstationData($goodweId);

        if ($data == null) {
            return $this->getData($goodweId);
        }
        
        return $data;
    }

    public function hourly()
    {
        // get Yahoo data
        $url = 'https://query.yahooapis.com/v1/public/yql?q=select%20item.condition%20from%20weather.forecast%20where%20woeid%20in%20(select%20woeid%20from%20geo.places(1)%20where%20text=%27Hoofddorp%27)%20and%20u=%27c%27&format=json';
        $response = json_decode(file_get_contents($url), true);
        $yahoo = $response['query']['results']['channel']['item']['condition'];

        $powerstations = collect($this->retriever->getAllPowerStationData()->data);

        foreach ($powerstations as $powerstation) {
            $entry[$this->lookup[$powerstation->powerstation_id]]['power'] = $powerstation->pac;
            $entry[$this->lookup[$powerstation->powerstation_id]]['temperature'] = $yahoo['temp'];
            $entry[$this->lookup[$powerstation->powerstation_id]]['condition'] = $yahoo['text'];
            $entry[$this->lookup[$powerstation->powerstation_id]]['code'] = $yahoo['code'];
        }

        return $entry;
    }

    public function daily()
    {
        $powerstations = collect($this->retriever->getAllPowerStationData()->data);

        foreach ($powerstations as $powerstation) {
            $entry[$this->lookup[$powerstation->powerstation_id]]['energy_today'] = intval($powerstation->eday) * 1000;
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
