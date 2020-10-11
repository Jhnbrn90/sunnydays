<?php

namespace App\Console\Commands;

use App\Mail\StatisticsMail;
use App\Models\DailyProductionLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailDailyYield extends Command
{
    protected $signature = 'sunnydays:mail-yield';

    protected $description = 'Send an e-mail reporting the total generated energy today';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->call('sunnydays:browsershot');

        $logs = DailyProductionLog::whereDate('created_at', Carbon::today())->get();

        Mail::to(config('app.mail'))->send(new StatisticsMail($logs));

        $this->info('Done');
    }
}
