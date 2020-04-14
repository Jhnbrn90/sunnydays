<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class GoodWeApi
{
    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getPowerStations()
    {
        if(Cache::has('all-powerstations')) {
            return Cache::get('all-powerstations');
        }

        if(Cache::missing('token')) {
            $this->refreshAccessToken();
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
        $url = config('goodwe.powerstation_monitor_url');

        $response = $this->httpClient->request('POST', $url, [
            'headers' => $this->getAllPowerStationHeaders(),
            'json' => $this->allPowerStationPostAttributes(),
        ]);

        return json_decode($response->getBody(), true);
    }

    private function retryRequest()
    {
        return $this->makeRequest();
    }

    private function getAllPowerStationHeaders()
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

    private function allPowerStationPostAttributes()
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

    private function refreshAccessToken()
    {
        $response = $this->login();

        Cache::put('token', $response['data']['token'], 120);
        Cache::put('uid', $response['data']['uid'], 120);
        Cache::put('timestamp', $response['data']['timestamp'], 120);
    }

    private function login()
    {
        $response = $this->httpClient->request(
            'POST',
            config('goodwe.login_url'),
            [
                'headers' => $this->getLoginHeaders(),
                'json' => [
                    'account' => config('goodwe.account'),
                    'pwd' => config('goodwe.password')
                ]
            ]
        );

        return json_decode($response->getBody(), true);
    }

    private function getLoginHeaders()
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