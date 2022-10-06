<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\StoreCurrentYield;
use App\Contracts\RetrieverInterface;
use App\DTO\GoodWePowerStation as PowerStationDTO;
use App\Models\Powerlog;
use App\Models\PowerStation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Services\FakeRetriever;
use Tests\TestCase;

class StoreCurrentYieldTest extends TestCase
{
    use RefreshDatabase;

    private FakeRetriever $retriever;
    private $powerStation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->retriever = new FakeRetriever;
        $this->powerStation = PowerStation::factory()->create();
    }

    /** @test */
    function it_registers_the_current_yield()
    {
        $this->swap(RetrieverInterface::class, $this->retriever);

        $this->retriever->returnPowerStation(new PowerStationDTO([
            'powerstation_id' => $this->powerStation->goodwe_id,
            'pac' => 1000,
            'eday' => 100,
        ]));

        $this->assertCount(0, Powerlog::all());

        $this->artisan(StoreCurrentYield::class);

        $this->assertCount(1, Powerlog::all());

        $this->assertDatabaseHas('powerlogs', [
            'current_power' => 1000,
            'power_station_id' => $this->powerStation->id,
        ]);

        $this->assertTrue(Powerlog::first()->powerstation->is($this->powerStation));
    }
}