<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class DataRetriever 
{
    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getAllPowerStationData()
    {
        if(Cache::has('all-powerstations')) {
            return Cache::get('all-powerstations');
        }

        if(Cache::missing('token')) {
            $this->setAccessTokens();
        }

        $response = $this->makeApiRequest();

        if ($response['data'] == null) {
            $response = $this->retryApiRequest();
        }

        Cache::put('all-powerstations', $response['data'], 120);

        return $response['data'];
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

    private function setAccessTokens()
    {
        $username = config('goodwe.account');
        $password = config('goodwe.password');

        $response = $this->login($username, $password);

        Cache::put('token', $response['data']['token'], 120);
        Cache::put('uid', $response['data']['uid'], 120);
        Cache::put('timestamp', $response['data']['timestamp'], 120);
    }

    private function logIn($username, $password)
    {
        $response = $this->httpClient->request(
            'POST',
            config('goodwe.login_url'),
            [
                'headers' => $this->getLoginHeaders(),
                'json' => [
                    'account' => $username,
                    'pwd' => $password
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

    private function makeApiRequest()
    {
        $url = config('goodwe.powerstation_monitor_url');

        $headers = $this->getAllPowerStationHeaders();

        $parameters = $this->allPowerStationPostAttributes();

        $response = $this->httpClient->request('POST', $url, [
            'headers' => $headers,
            'json' => $parameters,
        ]);

        return json_decode($response->getBody(), true);
    }

    private function retryApiRequest()
    {
        return $this->makeApiRequest();
    }
}