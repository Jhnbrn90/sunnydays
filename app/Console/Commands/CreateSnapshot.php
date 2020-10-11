<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;

class CreateSnapshot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sunnydays:browsershot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create snapshot of daily statistics';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Creating snapshots...');

        Browsershot::url(route('statistics'))
            ->windowSize(400, 350)
            ->setDelay(3000)
            ->save(public_path('images/statistics.png'));

        $this->info('Created current statistics snapshot');

        Browsershot::url(route('graph'))
            ->windowSize(900, 450)
            ->setDelay(3000)
            ->save(public_path('images/graph.png'));

        $this->info('Created current graph snapshot');

        Browsershot::url(route('weekly'))
            ->windowSize(900, 350)
            ->setDelay(3000)
            ->save(public_path('images/weekly.png'));

        $this->info('Created weekly statistics snapshot');

        $this->info('Done!');

        return 0;
    }
}