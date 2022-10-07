<?php

namespace App\Console\Commands;

use App\Models\PowerStation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateDailyProductionAverages extends Command
{
    protected $signature = 'sunnydays:averages';

    protected $description = 'Calculate and update daily production averages';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $powerStations = PowerStation::all();

        $powerStations->each(function ($powerStation) {
            $query = $powerStation->dailyProductionLogs();

            $query->when(config('sunnydays.average.offset') !== null, function ($query) {
                return $query->where('created_at', '>=', Carbon::createFromFormat('d-m-Y', config('sunnydays.average.offset')));
            });

            $logs = $query->get();
            $yield = $logs->sum('total_production') / 1000;
            $dataPoints = $logs->count();
            
            $sumOfDailyProductionYields = $powerStation->dailyProductionLogs()->sum('total_production');

            $powerStation->update([
                'daily_average' => number_format($yield / $dataPoints, 1),
                'total_energy' => $sumOfDailyProductionYields / 1000,
            ]);
        });

        $this->info('Updated all averages.');

        return 0;
    }
}
