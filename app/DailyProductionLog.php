<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DailyProductionLog extends Model
{
    protected $guarded = [];

    public function scopeThisWeek($query)
    {
        return $query
            ->whereBetween('created_at', [Carbon::today()->subDays(7), Carbon::today()])
            ->orderBy('created_at', 'ASC');
    }

    public function scopePositiveValues($query)
    {
        return $query->where('total_production', '>', 0);
    }
}
