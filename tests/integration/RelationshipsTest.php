<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class RelationshipsTest extends TestCase
{
    use DatabaseMigrations;

    public function testIncludeRelationships()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create();

        $devicevariation1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(['deviceId' => $device->id])->id;

        $devicevariation2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(['deviceId' => $device->id])->id;

        $this->json('GET', 'devices/'.$device->id.'/relationships/devicevariations')
            ->seeJsonStructure([
                'data' => [
                    0 => [
                        'type',
                        'id',
                        'attributes' => [],
                        'links' => [
                            'self',
                        ],
                    ],
                ],
                'meta' => [
                    'sort',
                    'filter',
                    'fields',
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages',
                    ],
                ],
                'links' => [
                    'self',
                    'first',
                    'last',
                ],
            ]);
    }

    public function testIncludeRelationshipsErrors()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create();

        $devicevariation1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(['deviceId' => $device->id])->id;

        $devicevariation2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(['deviceId' => $device->id])->id;

        $this->json('GET', 'notexists/'.$device->id.'/relationships/devicevariations')
            ->seeJson([
                'errors' => [
                    'notexists' => 'the Notexist selected doesn\'t exists',
                ],
            ]);

        $this->json('GET', 'devices/'.$device->id.'/relationships/notexists')
            ->seeJson([
                'errors' => [
                    'devices' => 'the notexists selected doesn\'t exists',
                ],
            ]);

        $deviceId = factory(\WA\DataStore\Device\Device::class)->create()->id;

        $res = $this->json('GET', 'devices/'.$deviceId.'/relationships/devicevariations')
            ->seeJsonStructure([
                'total',
                'per_page',
                'current_page',
                'last_page',
                'next_page_url',
                'prev_page_url',
                'from',
                'to',
                'data',
            ]);

        $idNotExists = $deviceId + 10;

        $this->json('GET', 'devices/'.$idNotExists.'/relationships/devicevariations')
            ->seeJson([
                'errors' => [
                    'devices' => 'the Device selected doesn\'t exists',
                ],
            ]);
    }

    public function testIncludeRelationshipsInformation()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create();

        $devicevariation1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(['deviceId' => $device->id])->id;

        $devicevariation2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(['deviceId' => $device->id])->id;

        $this->json('GET', 'devices/'.$device->id.'/devicevariations')
            ->seeJsonStructure(
            [
                'data' => [
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'priceRetail',
                            'price1',
                            'price2',
                            'priceOwn',
                            'deviceId',
                            'carrierId',
                            'companyId',
                            'created_at' => [
                                'date',
                                'timezone_type',
                                'timezone',
                            ],
                            'updated_at' => [
                                'date',
                                'timezone_type',
                                'timezone',
                            ],
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                ],
                'meta' => [
                    'sort',
                    'filter',
                    'fields',
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages',
                    ],
                ],
                'links' => [
                    'self',
                    'first',
                    'last',
                ],
            ]);
    }

    public function testIncludeRelationshipsInformationErrors()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create();

        $devicevariation1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(['deviceId' => $device->id])->id;

        $devicevariation2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(['deviceId' => $device->id])->id;

        $this->json('GET', 'notexists/'.$device->id.'/devicevariations')
            ->seeJson([
                'errors' => [
                    'notexists' => 'the Notexist selected doesn\'t exists',
                ],
            ]);

        $this->json('GET', 'devices/'.$device->id.'/notexists')
            ->seeJson([
                'errors' => [
                    'devices' => 'the notexists selected doesn\'t exists',
                ],
            ]);

        $deviceId = factory(\WA\DataStore\Device\Device::class)->create()->id;

        $res = $this->json('GET', 'devices/'.$deviceId.'/relationships/devicevariations')
            ->seeJsonStructure([
                'total',
                'per_page',
                'current_page',
                'last_page',
                'next_page_url',
                'prev_page_url',
                'from',
                'to',
                'data',
            ]);

        $idNotExists = $deviceId + 10;
        
        $this->json('GET', 'devices/'.$idNotExists.'/relationships/devicevariations')
            ->seeJson([
                'errors' => [
                    'devices' => 'the Device selected doesn\'t exists',
                ],
            ]);
    }
}