<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\StoreDailyYield;
use App\Contracts\RetrieverInterface;
use App\DTO\GoodWePowerStation as PowerStationDTO;
use App\Models\DailyProductionLog;
use App\Models\PowerStation as PowerStationModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Services\FakeRetriever;
use Tests\TestCase;

class StoreDailyYieldTest extends TestCase
{
    use RefreshDatabase;

    private $powerStation;
    private array $yield;

    protected function setUp(): void
    {
        parent::setUp();

        $this->powerStation = PowerStationModel::factory()->create();

        $this->yield = [
            'kWh' => 10,
            'W' => 10000,
        ];

        $powerStationDTO = new PowerStationDTO([
            'powerstation_id' => $this->powerStation->goodwe_id,
            'pac' => 100,
            'eday' => $this->yield['kWh'],
        ]);

        $retriever = new FakeRetriever;
        $this->swap(RetrieverInterface::class, $retriever);

        $retriever->returnPowerStation($powerStationDTO);
    }

    /** @test */
    public function it_stores_the_daily_yield()
    {
        $this->assertCount(0, DailyProductionLog::all());

        $this->artisan(StoreDailyYield::class);

        $this->assertCount(1, DailyProductionLog::all());

        $this->assertDatabaseHas('daily_production_logs', [
            'total_production' => $this->yield['W'],
            'power_station_id' => $this->powerStation->id,
        ]);

        $this->assertTrue(DailyProductionLog::first()->powerStation->is($this->powerStation));
    }
}