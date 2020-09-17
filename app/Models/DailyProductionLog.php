<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DailyProductionLog extends Model
{
    protected $guarded = [];

    public function scopeThisWeek($query)
    {
        $interval = [Carbon::today()->subDays(7), Carbon::today()];

        return $query
            ->whereBetween('created_at', $interval)
            ->orderBy('created_at', 'ASC');
    }

    public function scopePositiveValues($query)
    {
        return $query->where('total_production', '>', 0);
    }
}
