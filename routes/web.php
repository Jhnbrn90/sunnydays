<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShowGraphForDate;
use App\Http\Controllers\ShowWeekOverview;

Route::get('/', DashboardController::class);

Route::prefix('api')->group(function () {
    Route::get('live-chart/{date}', ShowGraphForDate::class)->name('live-chart');
    Route::get('week-overview', ShowWeekOverview::class)->name('week_overview');
});