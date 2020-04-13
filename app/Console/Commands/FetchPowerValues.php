<?php

namespace App\Console\Commands;

use App\Powerlog;
use Illuminate\Console\Command;

class FetchPowerValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sunnydays:store-currently-generating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the current power values from Goodwe';

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
        $url = config('app.url') . '/api/hourly';
        $response = json_decode(file_get_contents($url, true));

        foreach ($response as $user => $value) {
            if ($value->power > 50) {
                Powerlog::create([
                    'current_power'          => $value->power,
                    'weather_condition'      => $value->text,
                    'temperature'            => $value->temperature,
                    'weather_condition_code' => $value->code,
                    'user'                   => $user
                ]);
            }
        }

        return $this->info('Done');
    }
}
