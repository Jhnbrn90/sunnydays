<?php

namespace Tests\Feature\Commands;

use App\Contracts\RetrieverInterface;
use App\DTO\PowerStation as PowerStationDTO;
use App\Mail\HeartbeatMail;
use App\Models\PowerStation as PowerStationModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Feature\Services\FakeRetriever;
use Tests\TestCase;

class MailCurrentYieldTest extends TestCase
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
    function it_sends_an_email_with_the_power_station_name_and_current_yield()
    {
        Mail::fake();

        $this->artisan('sunnydays:mail-snapshot');

        Mail::assertSent(HeartbeatMail::class, function ($mail) {
            return collect($mail->values)->has($this->powerStation->name)
                && $mail->values[$this->powerStation->name] == $this->powerStationDTO->nowGenerating();
        });
    }
}