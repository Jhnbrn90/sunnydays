<?php

namespace Tests\Feature\Services;

use App\DTO\PowerStation;
use App\Models\PowerStation as PowerStationModel;
use App\Services\GoodWeApi;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class GoodWeApiTest extends TestCase
{
    function setUp(): void
    {
        parent::setUp();

        PowerStationModel::factory()->create([
            'name' => 'TEST',
            'goodwe_id' => 'fake-powerstation-id'
        ]);
    }

    /** @test */
    function it_keys_results_by_powerstation_owner()
    {
        $goodWeApiService = Mockery::mock(GoodWeApi::class);

        $this->app->instance(GoodWeApi::class, $goodWeApiService);

        $goodWeApiService
            ->shouldReceive('getPowerStations')
            ->andReturn(new Collection([
                new PowerStation($this->validParams([
                    'powerstation_id' => 'fake-powerstation-id',
                ]))
            ]));

        $apiResult = $this->get('/api/powerstations');

        $apiResult->assertJson([
            'TEST' => $this->validParams([
                'powerstation_id' => 'fake-powerstation-id'
            ])
        ]);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'powerstation_id' => 'fake-powerstation-id',
            'stationname' => 'TEST',
            'pac' => 1,
            'eday' => 2,
            'emonth' => 3,
            'etotal' => 4,
            'pac_kw' => 5,
            'is_stored' => false,
        ], $overrides);
    }
}
