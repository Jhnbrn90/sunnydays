<?php

namespace App;

class DataRetriever 
{
    protected $powerstationId;
    protected $token;
    protected $uid;
    protected $timestamp;
    protected $username;
    protected $password;
    protected $curl;
    protected $loginUrl = 'https://globalapi.sems.com.cn/api/v1/Common/CrossLogin';
    protected $powerStationUrl = 'https://euapi.sems.com.cn/api/v1/PowerStation/GetMonitorDetailByPowerstationId';

    public function __construct($powerstationId)
    {
        $this->powerstationId = $powerstationId;
        $this->username = config('goodwe.account');
        $this->password = config('goodwe.password');
    }

    public function getData()
    {
        return $this->setAccessTokens()
            ->getPowerStationData();
    }

    public function getPowerstationData()
    {
        $response = $this->setAccessTokens()
            ->initializeCurl()
            ->setUrl($this->powerStationUrl)
            ->setHeaders($this->getPowerStationHeaders())
            ->setPostAttributes(['powerStationId' => $this->powerstationId])
            ->getCurlResponse();

        return $response->data;
    }

    public function setAccessTokens()
    {
        $response = $this->initializeCurl()
            ->login($this->username, $this->password)
            ->getCurlResponse();
        
        $this->token = $response->data->token;
        $this->uid = $response->data->uid;
        $this->timestamp = $response->data->timestamp;

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

}
