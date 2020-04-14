<?php

return [
    'account'   => env('GOODWE_ACCOUNT'),
    'password'  => env('GOODWE_PASSWORD'),
    'login_url' => 'https://globalapi.sems.com.cn/api/v1/Common/CrossLogin',
    'powerstation_url' => 'https://euapi.sems.com.cn/api/v1/PowerStation/GetMonitorDetailByPowerstationId',
    'powerstation_monitor_url' => 'https://euapi.sems.com.cn/api/PowerStationMonitor/QueryPowerStationMonitorForApp',
    'users' => [
        'JL'    => env('GOODWE_ID'),
        'MB'    => env('GOODWE_ID_MB'),
        'BE'    => env('GOODWE_ID_BE'),
        'RB'    => env('GOODWE_ID_RB'),
    ]
];
