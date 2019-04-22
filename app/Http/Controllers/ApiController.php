<?php

namespace App\Http\Controllers;

use App\Powerlog;
use Carbon\Carbon;
use App\YahooWeather;
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

    public function weather()
    {
        $yahoo = new YahooWeather();
        return [
            'text'          => $yahoo->currentCondition()->text,
            'temperature'   => $yahoo->currentCondition()->temperature,
            'code'          => $yahoo->currentCondition()->code,
        ];   
    }

    public function hourly()
    {
        $yahoo = new YahooWeather();
        $currentWeather = $yahoo->currentCondition();

        $powerstations = collect($this->retriever->getAllPowerStationData()->data);

        foreach ($powerstations as $powerstation) {
            $entry[$this->lookup[$powerstation->powerstation_id]]['power'] = $powerstation->pac;
            $entry[$this->lookup[$powerstation->powerstation_id]]['temperature'] = $currentWeather->temperature;
            $entry[$this->lookup[$powerstation->powerstation_id]]['condition'] = $currentWeather->text;
            $entry[$this->lookup[$powerstation->powerstation_id]]['code'] = $currentWeather->code;
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
            $production = collect(DailyProductionLog::where('user', $user)->where('total_production', '>', 0)->pluck('total_production'));
            
            // $result[$user] = number_format(array_sum($production->toArray())/$production->count()/1000, 1);
            $result[$user] = number_format($production->sum() / 1000 / $production->count(), 1);

        }

        return collect($result);
    }
}
