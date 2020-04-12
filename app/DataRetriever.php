<?php

namespace App;

use Illuminate\Support\Facades\Cache;

class DataRetriever 
{
    protected $curl;

    public function getAllPowerStationData()
    {
        if(Cache::has('all-powerstations')) {
            return Cache::get('all-powerstations');
        }

        if(Cache::missing('token')) {
            $this->setAccessTokens();
        }

        $response = $this->makeApiRequest();

        if ($response->data == null) {
            $response = $this->retryApiRequest();
        }

        Cache::put('all-powerstations', $response->data, 120);

        return $response->data;
    }

    public function __toString()
    {
        return collect($this);
    }

    private function getAllPowerStationHeaders()
    {
        $token = sprintf(
            "Token: {%s,%s,%s,%s,%s,%s}",
            '"language":"en"',
            '"timestamp":' . Cache::get('timestamp'),
            '"uid":"' . Cache::get('uid') . '"',
            '"client":"ios"',
            '"token":"' . Cache::get('token') . '"',
            '"version":"v2.1.0"'
        );

        return [
            "Content-Type: application/json", 
            "Accept: */*", 
            "User-Agent: PVMaster/2.1.0 (iPhone; iOS 12.0; Scale/2.00)", 
            "Accept-Language: nl-BE;q=1",
            $token
        ];
    }

    private function initializeCurl()
    {
        $this->curl = curl_init();

        return $this;
    }

    private function setAccessTokens()
    {
        $username = config('goodwe.account');
        $password = config('goodwe.password');

        $response = $this->login($username, $password);

        Cache::put('token', $response->data->token, 120);
        Cache::put('uid', $response->data->uid, 120);
        Cache::put('timestamp', $response->data->timestamp, 120);

        return $this;
    }

    private function logIn($username, $password)
    {
        $this->initializeCurl();

        $this->setUrl(config('goodwe.login_url'));

        $this->setHeaders($this->getLoginHeaders());

        $this->setPostAttributes([
            'account' => $username,
            'pwd' => $password
        ]);

        return $this->getCurlResponse();
    }

    private function getCurlResponse()
    {
        $response = curl_exec($this->curl);
        
        curl_close($this->curl);

        return json_decode($response);
    }

    private function setUrl($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, 1);

        return $this;
    }

    private function setPostAttributes($attributes)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($attributes));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        return $this;
    }

    private function setHeaders($headers)
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

        return $this;
    }

    private function getLoginHeaders()
    {
        return [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding: gzip, deflate',
            'Accept: */*',
            'Connect: Keep-alive',
            'Content-Type: application/json',
            'Host: globalapi.sems.com.cn',
            'Token: {"version":"v2.1.0","client":"ios","language":"en"}',
            'User-Agent:  PVMaster/2.1.0 (iPhone; iOS 12.0; Scale/2.00)',
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
        return $this
            ->initializeCurl()
            ->setUrl(config('goodwe.powerstation_monitor_url'))
            ->setHeaders($this->getAllPowerStationHeaders())
            ->setPostAttributes($this->allPowerStationPostAttributes())
            ->getCurlResponse();
    }

    private function retryApiRequest()
    {
        return $this->makeApiRequest();
    }
}
