<?php

return [
    'app_id'            => env('YAHOO_APP_ID', null),
    'consumer_key'      => env('YAHOO_CONSUMER_KEY', null),
    'consumer_secret'   => env('YAHOO_CONSUMER_SECRET', null),
    'location'          => 'hoofddorp,NL',
    'url'               => 'https://weather-ydn-yql.media.yahoo.com/forecastrss',
    'temperature_unit'  => 'c', // degrees Celcius
];
