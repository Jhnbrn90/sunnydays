<?php

namespace Tests\Feature\Services;

use App\Contracts\RetrieverInterface;
use App\DTO\PowerStation;
use App\DTO\PowerStationDTOCollection;
use App\Models\PowerStation as PowerStationModel;
use App\Services\GoodWeApi;
use Mockery;
use Tests\TestCase;

class GoodWeApiTest extends TestCase
{
    function setUp(): void
    {
        parent::setUp();

        PowerStationModel::factory()->create([
            'name' => 'TEST',
            'goodwe_id' => 'fake-powerstation-id',
            'line_color' => '41, 50, 33'
        ]);
    }

    /** @test */
    function it_returns_all_power_stations()
    {
        $goodWeApiService = Mockery::mock(GoodWeApi::class);

        $this->app->instance(GoodWeApi::class, $goodWeApiService);
        $this->app->instance(RetrieverInterface::class, $goodWeApiService);

        $goodWeApiService
            ->shouldReceive('getPowerStations')
            ->andReturn(new PowerStationDTOCollection([
                new PowerStation($this->validParams([
                    'powerstation_id' => 'fake-powerstation-id',
                ]))
            ]));

        $powerStations = app(RetrieverInterface::class)
            ->getPowerStations()
            ->registered()
            ->toArray();

        $expectedArray = [
            'owner' => [
                'name' => 'TEST',
                'color' => '41, 50, 33'
            ],
            'working' => false,
            'generating' => 1,
            'today' => 2.0,
            'month' => 3.0,
            'total' => 4,
            'average' => 0.0
        ];

        $this->assertEquals($expectedArray, $powerStations[0]);
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
