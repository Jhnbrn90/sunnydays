<?php

namespace App\Services;

use App\Contracts\RetrieverInterface;
use App\DTO\PowerStation;
use App\DTO\PowerStationDTOCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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

    public function getPowerStations(): PowerStationDTOCollection
    {
        if (Cache::has('all-powerstations')) {
            return Cache::get('all-powerstations');
        }

        $response = $this->makeRequest();

        $powerStations = (new PowerStationDTOCollection($response['data']))
            ->map(function (array $powerStation) {
                return new PowerStation($powerStation);
            });

        Cache::put('all-powerstations', $powerStations, now()->addMinutes(2));

        return $powerStations;
    }

    private function makeRequest()
    {
        if (Cache::missing('token')) {
            $this->login();
        }

        $response = Http::withoutVerifying()->withHeaders($this->resourceHeaders())
            ->post(self::RESOURCE_URL, [
                'page_index' => '1'
            ]);

        if ($response->json()['data'] == null) {
            sleep(10);
            return $this->makeRequest();
        }

        if (is_array($response)) {
            sleep(10);
            return $this->makeRequest();
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
        $response = Http::withoutVerifying()->withHeaders($this->loginHeaders())
            ->post(self::LOGIN_URL, ['account' => $this->account, 'pwd' => $this->password])
            ->json();

        Cache::put('token', $response['data']['token'], now()->addMinutes(2));
        Cache::put('uid', $response['data']['uid'], now()->addMinutes(2));
        Cache::put('timestamp', $response['data']['timestamp'], now()->addMinutes(2));
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