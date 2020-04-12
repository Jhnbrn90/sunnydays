<?php

namespace App\Console\Commands;

use App\DailyProductionLog;
use App\Mail\StatisticsMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class LogDailyProducedEnergy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sunnydays:store-daily-produced-energy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store the daily total generated energy';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = config('app.url') . '/api/daily';
        $response = json_decode(file_get_contents($url, true));

        $log = [];

        foreach ($response as $user => $value) {
            $logs[$user] = DailyProductionLog::create([
                'total_production'  => $value->energy_today,
                'user'              => $user,
            ]);
        }

        Mail::to(config('app.mail'))->send(new StatisticsMail($logs));

        return $this->info('Done');
    }
}
