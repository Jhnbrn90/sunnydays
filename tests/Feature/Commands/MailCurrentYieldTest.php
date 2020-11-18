<?php

namespace Tests\Feature\Commands;

use App\Console\Commands\MailCurrentYield;
use App\Contracts\RetrieverInterface;
use App\DTO\PowerStation as PowerStationDTO;
use App\Mail\CurrentYieldMail;
use App\Models\PowerStation;
use App\Models\PowerStation as PowerStationModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\Feature\Services\FakeRetriever;
use Tests\TestCase;

class MailCurrentYieldTest extends TestCase
{
    use RefreshDatabase;

    private PowerStation $powerStation;
    private PowerStationDTO $powerStationDTO;

    protected function setUp(): void
    {
        parent::setUp();

        $this->powerStation = PowerStationModel::factory()->create();

        $this->powerStationDTO = new PowerStationDTO([
            'powerstation_id' => $this->powerStation->goodwe_id,
            'pac' => 100,
            'eday' => 1000,
        ]);

        $retriever = new FakeRetriever;
        $this->swap(RetrieverInterface::class, $retriever);

        $retriever->returnPowerStation($this->powerStationDTO);
    }

    /** @test */
    function it_sends_an_email_with_the_power_station_name_and_current_yield()
    {
        Mail::fake();

        $this->artisan(MailCurrentYield::class);

        Mail::assertSent(CurrentYieldMail::class, function ($mail) {
            return collect($mail->currentYields)->has($this->powerStation->name)
                && $mail->currentYields[$this->powerStation->name] == $this->powerStationDTO->nowGenerating();
        });
    }
}