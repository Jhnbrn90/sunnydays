<?php

namespace Tests\Feature\Commands;

use App\Contracts\RetrieverInterface;
use App\DTO\PowerStation as PowerStationDTO;
use App\Mail\StatisticsMail;
use App\Models\PowerStation as PowerStationModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Feature\Services\FakeRetriever;
use Tests\TestCase;

class StoreDailyYieldTest extends TestCase
{
    use RefreshDatabase;

    private $retriever;
    private $powerStation;
    private $powerStationDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->powerStation = PowerStationModel::factory()->create();

        $this->powerStationDTO = new PowerStationDTO([
            'powerstation_id' => $this->powerStation->goodwe_id,
            'pac' => 100,
            'eday' => 1000,
        ]);

        $this->retriever = new FakeRetriever;
        $this->swap(RetrieverInterface::class, $this->retriever);

        $this->retriever->returnPowerStation($this->powerStationDTO);
    }

    /** @test */
    function it_sends_an_email()
    {
        Mail::fake();

        // act

        // assert
        Mail::assertSent(StatisticsMail::class);
    }
}