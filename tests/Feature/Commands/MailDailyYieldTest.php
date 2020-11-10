<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\MailDailyYield;
use App\Mail\StatisticsMail;
use App\Models\PowerStation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailDailyYieldTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_can_send_a_daily_yield_email()
    {
        Mail::fake();

        $powerStation = PowerStation::factory()->create();

        $powerStation->dailyProductionLogs()->create([
            'created_at' => Carbon::today(),
            'total_production' => 1000,
        ]);

        $this->artisan(MailDailyYield::class);

        Mail::assertSent(StatisticsMail::class, function ($mail) use ($powerStation) {
            $log = $mail->logs[0];
            return $log->total_production === 1000
                && $log->powerStation->is($powerStation)
                && $log->created_at->isToday();
        });
    }

    /** @test */
    public function daily_yield_email_includes_all_power_station_logs()
    {
        Mail::fake();

        $powerStations = PowerStation::factory(2)->create();

        $powerStations[0]->dailyProductionLogs()->create([
            'created_at' => Carbon::today(),
            'total_production' => 1000,
        ]);

        $powerStations[1]->dailyProductionLogs()->create([
            'created_at' => Carbon::today(),
            'total_production' => 2000,
        ]);

        $this->artisan(MailDailyYield::class);

        Mail::assertSent(StatisticsMail::class, function ($mail) use ($powerStations) {
            return $mail->logs->count() === 2
                && $mail->logs[0]->power_station_id === $powerStations[0]->id
                && $mail->logs[1]->power_station_id === $powerStations[1]->id
                && $mail->logs[0]->total_production === 1000
                && $mail->logs[1]->total_production === 2000;
        });
    }
}