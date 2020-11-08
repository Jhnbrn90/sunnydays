<?php

namespace App\Console\Commands;

use App\Models\PowerStation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateDailyProductionAverages extends Command
{
    protected $signature = 'sunnydays:averages';

    protected $description = 'Calculate and update daily production averages';

    private $offset;

    public function __construct()
    {
        parent::__construct();

        $this->offset = '01-09-2020'; // extract to config / ENV variable
    }

    public function handle()
    {
        $powerStations = PowerStation::all();

        $powerStations->each(function ($powerStation) {
            $query = $powerStation->dailyProductionLogs();

            $query->when($this->offset !== null, function ($query) {
                return $query->where(
                    'created_at',
                    '>',
                    Carbon::createFromFormat('d-m-Y', $this->offset)
                );
            });

            $logs = $query->get();

            $yield = $logs->sum('total_production') / 1000;

            $dataPoints = $logs->count();

            $powerStation->update([
                'daily_average' => number_format($yield / $dataPoints, 1)
            ]);
        });

        $this->info('Updated all averages.');

        return 0;
    }
}
