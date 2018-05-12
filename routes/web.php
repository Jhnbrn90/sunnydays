<?php

Route::get('/', 'IndexController@show');

Route::prefix('api')->group(function () {
    Route::get('goodwe/{id}', 'ApiController@goodWe');
    Route::get('hourly', 'ApiController@hourly');
    Route::get('daily', 'ApiController@daily');
    Route::get('production', 'ApiController@production');
    Route::get('dailygraph/{date}', 'ApiController@dailyGraph');
    Route::get('average', 'ApiController@getAverage');
});
