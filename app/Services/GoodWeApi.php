<?php

namespace App\Services;

use App\Contracts\RetrieverInterface;
use App\DTO\PowerStation;
use Illuminate\Support\Facades\Cache;
use Zttp\Zttp;

class GoodWeApi implements RetrieverInterface
{
    const LOGIN_URL = 'https://globalapi.sems.com.cn/api/v1/Common/CrossLogin';
    const RESOURCE_URL = 'https://euapi.sems.com.cn/api/PowerStationMonitor/QueryPowerStationMonitorForApp';

    protected $account;
    protected $password;

    public function __construct()
    {
        $this->account = config('goodwe.account');
        $this->password = config('goodwe.password');
    }

    public function getPowerStations()
    {
        if (Cache::has('all-powerstations')) {
            return Cache::get('all-powerstations');
        }

        $response = $this->makeRequest();

        $powerStations = collect($response['data'])->map(function (array $powerStation) {
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

        $response = Zttp::withoutVerifying()->withHeaders($this->resourceHeaders())
            ->post(self::RESOURCE_URL, [
                'page_index' => '1'
            ]);

        if ($response->json()['data'] == null) {
            $response = $this->makeRequest();
        }

        return $response->json();
    }

    private function resourceHeaders()
    {
        return [
            "Content-Type" => "application/json",
            "Accept" => "*/*",
            "User-Agent" => "PVMaster/2.1.0 (iPhone; iOS 12.0; Scale/2.00)",
            "Accept-Language" => "nl-BE;q=1",
            "Token" => sprintf(
                "{%s,%s,%s,%s,%s,%s}",
                '"language":"en"',
                '"timestamp":' . Cache::get('timestamp'),
                '"uid":"' . Cache::get('uid') . '"',
                '"token":"' . Cache::get('token') . '"',
                '"client":"ios"',
                '"version":"v2.1.0"'
            )
        ];
    }

    private function login()
    {
        $response = Zttp::withoutVerifying()->withHeaders($this->loginHeaders())
            ->post(self::LOGIN_URL, ['account' => $this->account, 'pwd' => $this->password])
            ->json();

        Cache::put('token', $response['data']['token'], 120);
        Cache::put('uid', $response['data']['uid'], 120);
        Cache::put('timestamp', $response['data']['timestamp'], 120);
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