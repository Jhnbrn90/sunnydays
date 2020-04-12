<?php

Route::get('/', 'IndexController');

Route::prefix('api')->group(function () {
    Route::get('powerstation/{powerstation}', 'ApiController@getPowerstation');
    Route::get('goodwe/all', 'ApiController@goodWeAll');
    Route::get('goodwe/{id}', 'ApiController@goodWe');
    Route::get('hourly', 'ApiController@hourly');
    Route::get('daily', 'ApiController@daily');
    Route::get('production', 'ApiController@production');
    Route::get('dailygraph/{date}', 'ApiController@dailyGraph');
    Route::get('average', 'ApiController@getAverage');
    Route::get('weather', 'ApiController@weather');
});
