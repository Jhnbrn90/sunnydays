<?php

namespace App;

use Illuminate\Support\Facades\Cache;
use Zttp\Zttp;

class GoodWeApi
{
    const LOGIN_URL = 'https://globalapi.sems.com.cn/api/v1/Common/CrossLogin';
    const RESOURCE_URL = 'https://euapi.sems.com.cn/api/PowerStationMonitor/QueryPowerStationMonitorForApp';

    protected $account;
    protected $password;

    public function __construct(string $account, string $password)
    {
        $this->account = $account;
        $this->password = $password;
    }

    public function getPowerStations()
    {
        if(Cache::has('all-powerstations')) {
            return Cache::get('all-powerstations');
        }

        $response = $this->makeRequest();

        if ($response['data'] == null) {
            $response = $this->retryRequest();
        }

        $powerStations = collect($response['data'])->map(function ($powerStation) {
            return new PowerStation($powerStation);
        });

        Cache::put('all-powerstations', $powerStations, 120);

        return $powerStations;
    }

    private function makeRequest()
    {
        if (Cache::missing('token')) {
            $this->login();
        }

        $response = Zttp::withHeaders($this->resourceHeaders())
            ->post(self::RESOURCE_URL, $this->resourceAttributes());

        return $response->json();
    }

    private function login()
    {
        $response = Zttp::withHeaders($this->loginHeaders())
            ->post(self::LOGIN_URL, ['account' => $this->account, 'pwd' => $this->password])
            ->json();

        Cache::put('token', $response['data']['token'], 120);
        Cache::put('uid', $response['data']['uid'], 120);
        Cache::put('timestamp', $response['data']['timestamp'], 120);
    }

    private function retryRequest()
    {
        return $this->makeRequest();
    }

    private function resourceHeaders()
    {
        $token = sprintf(
            "{%s,%s,%s,%s,%s,%s}",
            '"language":"en"',
            '"timestamp":' . Cache::get('timestamp'),
            '"uid":"' . Cache::get('uid') . '"',
            '"client":"ios"',
            '"token":"' . Cache::get('token') . '"',
            '"version":"v2.1.0"'
        );

        return [
            "Content-Type" => "application/json",
            "Accept" => "*/*",
            "User-Agent" => "PVMaster/2.1.0 (iPhone; iOS 12.0; Scale/2.00)",
            "Accept-Language" => "nl-BE;q=1",
            "Token" => $token
        ];
    }

    private function resourceAttributes()
    {
        return [
            'page_size' => '5',
            'order_by'  => '',
            'powerstation_status' => '',
            'key' => '',
            'page_index' => '1',
            'powerstation_id' => '',
            'power_station_type' => ''
        ];
    }

    private function loginHeaders()
    {
        return [
            'Accept-Encoding' => 'gzip, deflate',
            'Accept' => '*/*',
            'Connect' => 'Keep-alive',
            'Content-Type' => 'application/json',
            'Host' => 'globalapi.sems.com.cn',
            'Token' => '{"version":"v2.1.0","client":"ios","language":"en"}',
            'User-Agent' => 'PVMaster/2.1.0 (iPhone; iOS 12.0; Scale/2.00)',
        ];
    }
}