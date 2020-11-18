<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\UpdateDailyProductionAverages;
use App\Models\PowerStation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class UpdateDailyProductionAveragesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_all_daily_production_averages()
    {
        Config::set(['sunnydays.average.offset' => null]);

        $powerStation = PowerStation::factory()->create([
            'daily_average' => 1,
        ]);

        $powerStation->dailyProductionLogs()->create([
            'total_production' => 1000,
            'created_at' => now()->subWeek(),
        ]);

        $powerStation->dailyProductionLogs()->create([
            'total_production' => 2000,
            'created_at' => now(),
        ]);

        $this->artisan(UpdateDailyProductionAverages::class);

        $this->assertEquals(1.5, $powerStation->fresh()->daily_average);
    }

    /** @test */
    public function it_can_calculate_the_average_yield_with_an_offset()
    {
        Config::set(['sunnydays.average.offset' => null]);

        $powerStation = PowerStation::factory()->create([
            'daily_average' => 1,
        ]);

        $powerStation->dailyProductionLogs()->create([
            'total_production' => 1000,
            'created_at' => Carbon::createFromFormat('d-m-Y', '01-01-2020'),
        ]);

        $powerStation->dailyProductionLogs()->create([
            'total_production' => 1000,
            'created_at' => Carbon::createFromFormat('d-m-Y', '01-04-2020'),
        ]);

        $powerStation->dailyProductionLogs()->create([
            'total_production' => 1000,
            'created_at' => Carbon::createFromFormat('d-m-Y', '01-06-2020'),
        ]);

        $powerStation->dailyProductionLogs()->create([
            'total_production' => 2000,
            'created_at' => Carbon::createFromFormat('d-m-Y', '01-09-2020'),
        ]);

        $this->artisan(UpdateDailyProductionAverages::class);

        $this->assertEquals(1.3, $powerStation->fresh()->daily_average);

        // Given we have an offset, starting from '01-06-2020'
        $offset = Carbon::createFromFormat('d-m-Y', '01-06-2020');
        Config::set(['sunnydays.average.offset' => $offset->format('d-m-Y')]);

        $this->artisan(UpdateDailyProductionAverages::class);

        $this->assertEquals(1.5, $powerStation->fresh()->daily_average);
    }
}