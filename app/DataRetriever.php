<?php

namespace App;

use Illuminate\Support\Facades\Cache;

class DataRetriever 
{
    protected $token;
    protected $uid;
    protected $timestamp;
    protected $username;
    protected $password;
    protected $curl;
    protected $loginUrl = 'https://globalapi.sems.com.cn/api/v1/Common/CrossLogin';
    protected $powerStationUrl = 'https://euapi.sems.com.cn/api/v1/PowerStation/GetMonitorDetailByPowerstationId';

    public function __construct()
    {
        $this->username = config('goodwe.account');
        $this->password = config('goodwe.password');
    }

    public function getAllPowerStationData()
    {
        if(Cache::has('all-powerstations')) {
            return Cache::get('all-powerstations');
        }

        if(Cache::has('token')) {
            $this->token = Cache::get('token');
            $this->uid = Cache::get('uid');
            $this->timestamp = Cache::get('timestamp');
        } else {
            $this->setAccessTokens();
        }

        $response = $this
             ->initializeCurl()
             ->setUrl("https://euapi.sems.com.cn/api/PowerStationMonitor/QueryPowerStationMonitorForApp")
             ->setHeaders($this->getAllPowerStationHeaders())
             ->setPostAttributes([
                 'page_size' => '5',
                 'order_by'  => '',
                 'powerstation_status' => '',
                 'key'   => '',
                 'page_index' => '1',
                 'powerstation_id'   => '',
                 'power_station_type'    => ''
             ])
             ->getCurlResponse();

        if ($response->data == null) {
            $response = $this
                 ->initializeCurl()
                 ->setUrl("https://euapi.sems.com.cn/api/PowerStationMonitor/QueryPowerStationMonitorForApp")
                 ->setHeaders($this->getAllPowerStationHeaders())
                 ->setPostAttributes([
                     'page_size' => '5',
                     'order_by'  => '',
                     'powerstation_status' => '',
                     'key'   => '',
                     'page_index' => '1',
                     'powerstation_id'   => '',
                     'power_station_type'    => ''
                 ])
                 ->getCurlResponse();
        }

            Cache::put('all-powerstations', $response, 2);

        return $response;
    }

    public function getAllPowerStationHeaders()
    {
        return [
            "Content-Type: application/json", 
            "Accept: */*", 
            "User-Agent: PVMaster/2.1.0 (iPhone; iOS 12.0; Scale/2.00)", 
            "Accept-Language: nl-BE;q=1",
            "Token: {\"language\":\"en\",\"timestamp\":". $this->timestamp .",\"uid\":\"". $this->uid ."\",\"client\":\"ios\",\"token\":\"". $this->token ."\",\"version\":\"v2.1.0\"}"
        ];
    }

    public function getPowerstationData($goodweId)
    {
        $response = $this
            ->initializeCurl()
            ->setUrl($this->powerStationUrl)
            ->setHeaders($this->getPowerStationHeaders())
            ->setPostAttributes(['powerStationId' => $goodweId])
            ->getCurlResponse();

        return $response->data;
    }

    protected function setAccessTokens()
    {
        $response = $this->initializeCurl()
            ->login($this->username, $this->password)
            ->getCurlResponse();
        
        $this->token = $response->data->token;
        $this->uid = $response->data->uid;
        $this->timestamp = $response->data->timestamp;

        Cache::put('token', $this->token, 2);
        Cache::put('uid', $this->uid, 2);
        Cache::put('timestamp', $this->timestamp, 2);

        return $this;
    }

    protected function initializeCurl()
    {
        $this->curl = curl_init();

        return $this;
    }

    protected function logIn($username, $password)
    {
        $this->setUrl($this->loginUrl);

        $this->setHeaders($this->getLoginHeaders());

        $this->setPostAttributes(['account' => $username, 'pwd' => $password]);

        return $this;
    }

    protected function getCurlResponse()
    {
        $response = curl_exec($this->curl);
        
        curl_close($this->curl);

        return json_decode($response);
    }

    protected function setUrl($url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, 1);

        return $this;
    }

    protected function setPostAttributes($attributes)
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($attributes));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        return $this;
    }

    protected function setHeaders($headers)
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

        return $this;
    }

    protected function getLoginHeaders()
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

    protected function getPowerStationHeaders()
    {
        return [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding: gzip, deflate',
            'Accept: */*',
            'Connect: Keep-alive',
            'Content-Type: application/json',
            'Host: globalapi.sems.com.cn',
            'Token: {"version":"v2.1.0","client":"ios","language":"en","timestamp":'.$this->timestamp.',"uid":"'.$this->uid.'","token":"'.$this->token.'"}',
            'User-Agent:  PVMaster/2.1.0 (iPhone; iOS 12.0; Scale/2.00)',
        ];
    }

    public function __toString()
    {
        return collect($this);
    }

}
