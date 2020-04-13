<?php

namespace App\Console\Commands;

use App\Mail\HeartbeatMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sunnydays:send-daily-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily mail reporting the currently generated power';

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

        $values = [];

        foreach ($response as $user => $value) {
            $values[$user] = $value->power;
            $weather['condition'] = $value->text;
            $weather['temperature'] = $value->temperature;
        }

        Mail::to(config('app.mail'))->send(new HeartbeatMail($values, $weather));

        return $this->info('Done');
    }
}
