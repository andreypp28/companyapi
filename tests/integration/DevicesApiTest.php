<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use WA\DataStore\Device\Device;
use WA\DataStore\Company\Company;
use WA\DataStore\Carrier\Carrier;

class DevicesApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetDevices()
    {
        factory(\WA\DataStore\Device\Device::class, 40)->create();

        $res = $this->json('GET', 'devices');

        $res->seeJsonStructure([
            'data' => [
                0 => [
                    'type',
                    'id',
                    'attributes' => [
                        'identification',
                        'name',
                        'properties',
                        'externalId',
                        'statusId',
                        'syncId',
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
                'next',
                'last',
            ],
        ]);
    }

    public function testGetDeviceByIdIfExists()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create(['externalId' => 1]);

        $res = $this->json('GET', 'devices/'.$device->id)
            ->seeJson([
                'type' => 'devices',
                'identification' => $device->identification,
                'name' => $device->name,
                'properties' => $device->properties,
                'externalId' => $device->externalId,
                'statusId' => $device->statusId,
                'syncId' => $device->syncId,
            ]);

        $res->seeJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'identification',
                    'name',
                    'properties',
                    'externalId',
                    'statusId',
                    'syncId',
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
        ]);
    }

    public function testGetDeviceByIdIfNoExists()
    {
        $deviceId = factory(\WA\DataStore\Device\Device::class)->create()->id;
        $deviceId = $deviceId + 10;

        $response = $this->call('GET', '/devices/'.$deviceId);
        $this->assertEquals(404, $response->status());
    }

    public function testGetDeviceByIdandIncludesImages()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create();

        $image1 = factory(\WA\DataStore\Image\Image::class)->create()->id;
        $image2 = factory(\WA\DataStore\Image\Image::class)->create()->id;

        $dataImages = array($image1, $image2);

        $device->images()->sync($dataImages);

        $response = $this->get('/devices/'.$device->id.'?include=images')
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'identification',
                        'name',
                        'properties',
                        'externalId',
                        'statusId',
                        'syncId',
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
                    'relationships' => [
                        'images' => [
                            'links' => [
                                'self',
                                'related',
                            ],
                            'data' => [
                                0 => [
                                    'type',
                                    'id',
                                ],
                            ],
                        ],
                    ],
                ],
                'included' => [
                    1 => [
                        'type',
                        'id',
                        'attributes' => [
                            'originalName',
                            'filename',
                            'mimeType',
                            'extension',
                            'size',
                        ],
                        'links' => [
                            'self',
                        ],
                    ],

                ],
            ]);
    }

    public function testCreateDevice()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create();

        $imag1 = factory(\WA\DataStore\Image\Image::class)->create()->id;
        $imag2 = factory(\WA\DataStore\Image\Image::class)->create()->id;

        $modCap1 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;
        $modSty2 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'style'])->id;
        $modCap3 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;

        $carr1 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;
        $carr2 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;

        $comp1 = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $comp2 = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $device = $this->post('/devices',
            [
                'data' => [
                    'type' => 'devices',
                    'attributes' => [
                        'name' => 'whenIneedMotivation...',
                        'properties' => 'MyOneSolutionIsMyQueen',
                        'deviceTypeId' => $device->deviceTypeId,
                        'statusId' => 1,
                        'externalId' => 2,
                        'identification' => rand(9000000000000, 9999999999999),
                    ],
                    'relationships' => [
                        'images' => [
                            'data' => [
                                ['type' => 'images', 'id' => $imag1],
                                ['type' => 'images', 'id' => $imag2],
                            ],
                        ],
                        'modifications' => [
                            'data' => [
                                ['type' => 'modifications', 'id' => $modCap1],
                                ['type' => 'modifications', 'id' => $modSty2],
                                ['type' => 'modifications', 'id' => $modCap3],
                            ],
                        ],
                        'devicevariations' => [
                            'data' => [
                                ['type' => 'devicevariations', 'carrierId' => $carr1, 'companyId' => $comp1, 'priceRetail' => 100, 'price1' => 100, 'price2' => 100, 'priceOwn' => 100],
                                ['type' => 'devicevariations', 'carrierId' => $carr1, 'companyId' => $comp2, 'priceRetail' => 200, 'price1' => 200, 'price2' => 200, 'priceOwn' => 200],
                                ['type' => 'devicevariations', 'carrierId' => $carr2, 'companyId' => $comp1, 'priceRetail' => 300, 'price1' => 300, 'price2' => 300, 'priceOwn' => 300],
                                ['type' => 'devicevariations', 'carrierId' => $carr2, 'companyId' => $comp2, 'priceRetail' => 400, 'price1' => 400, 'price2' => 400, 'priceOwn' => 400],
                                ['type' => 'devicevariations', 'carrierId' => $carr1, 'companyId' => $comp1, 'priceRetail' => 500, 'price1' => 500, 'price2' => 500, 'priceOwn' => 500],
                                ['type' => 'devicevariations', 'carrierId' => $carr1, 'companyId' => $comp2, 'priceRetail' => 600, 'price1' => 600, 'price2' => 600, 'priceOwn' => 600],
                                ['type' => 'devicevariations', 'carrierId' => $carr2, 'companyId' => $comp1, 'priceRetail' => 700, 'price1' => 700, 'price2' => 700, 'priceOwn' => 700],
                                ['type' => 'devicevariations', 'carrierId' => $carr2, 'companyId' => $comp2, 'priceRetail' => 800, 'price1' => 800, 'price2' => 800, 'priceOwn' => 800],
                            ],
                        ],
                    ],
                ],
            ]
            )->seeJson(
            [
                'type' => 'devices',
                'name' => 'whenIneedMotivation...',
                'properties' => 'MyOneSolutionIsMyQueen',
                'statusId' => 1,
                'externalId' => 2,
            ]);
    }

    public function testCreateDeviceReturnNoValidData()
    {
        // 'data' no valid.
        $device = $this->post('/devices',
            [
                'NoValid' => [
                    ],
            ]
            )->seeJson(
            [
                'errors' => [
                    'json' => 'JSON is Invalid',
                ],
            ]
        );
    }

    public function testCreateDeviceReturnNoValidType()
    {
        // 'type' no valid.
        $device = $this->post('/devices',
            [
                'data' => [
                    'NoValid' => 'devices',
                    'attributes' => [
                        'name' => 'whenIneedMotivation...',
                        'properties' => 'MyOneSolutionIsMyQueen',
                    ],
                ],

            ]
            )->seeJson(
            [
                'errors' => [
                    'json' => 'JSON is Invalid',
                ],
            ]
        );
    }

    public function testCreateDeviceReturnNoValidAttributes()
    {
        // 'attributes' no valid.
        $device = $this->post('/devices',
            [
                'data' => [
                    'type' => 'devices',
                    'NoValid' => [
                        'name' => 'whenIneedMotivation...',
                        'properties' => 'MyOneSolutionIsMyQueen',
                    ],
                ],
            ]
            )->seeJson(
            [
                'errors' => [
                    'json' => 'JSON is Invalid',
                ],
            ]
        );
    }

    public function testCreateDeviceReturnRelationshipNoExists()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create();
        $deviceTypeId = $device->deviceTypeId;

        $device = $this->json('POST', '/devices',
        [
            'data' => [
                'type' => 'devices',
                'attributes' => [
                    'identification' => rand(9000000000000, 9999999999999),
                    'name' => 'whenIneedMotivation...',
                    'properties' => 'MyOneSolutionIsMyQueen',
                    'deviceTypeId' => $deviceTypeId,
                ],
                'relationships' => [
                    'IgnoreType' => [
                        'data' => [
                            ['type' => 'assets', 'id' => '1'],
                            ['type' => 'assets', 'id' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'devices',
            'name' => 'whenIneedMotivation...',
            'properties' => 'MyOneSolutionIsMyQueen',
        ]);
    }

    public function testCreateDeviceReturnRelationshipNoExistsData()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create();
        $deviceTypeId = $device->deviceTypeId;

        $device = $this->post('/devices',
        [
            'data' => [
                'type' => 'devices',
                'attributes' => [
                    'identification' => rand(9000000000000, 9999999999999),
                    'name' => 'whenIneedMotivation...',
                    'properties' => 'MyOneSolutionIsMyQueen',
                    'deviceTypeId' => $deviceTypeId,
                ],
                'relationships' => [
                    'assets' => [
                        'IgnoreType' => [
                            ['type' => 'assets', 'id' => '1'],
                            ['type' => 'assets', 'id' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'devices',
            'name' => 'whenIneedMotivation...',
            'properties' => 'MyOneSolutionIsMyQueen',
        ]);
    }

    public function testCreateDeviceReturnRelationshipNoAssetsType()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create();
        $deviceTypeId = $device->deviceTypeId;

        $device = $this->post('/devices',
        [
            'data' => [
                'type' => 'devices',
                'attributes' => [
                    'identification' => rand(9000000000000, 9999999999999),
                    'name' => 'whenIneedMotivation...',
                    'properties' => 'MyOneSolutionIsMyQueen',
                    'deviceTypeId' => $deviceTypeId,
                ],
                'relationships' => [
                    'assets' => [
                        'data' => [
                            ['type' => 'NoAssets', 'id' => '1'],
                            ['type' => 'NoAssets', 'id' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'devices',
            'name' => 'whenIneedMotivation...',
            'properties' => 'MyOneSolutionIsMyQueen',
        ]);
    }

    public function testCreateDeviceReturnRelationshipNoIdExists()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create();
        $deviceTypeId = $device->deviceTypeId;

        $device = $this->post('/devices',
        [
            'data' => [
                'type' => 'devices',
                'attributes' => [
                    'identification' => rand(9000000000000, 9999999999999),
                    'name' => 'whenIneedMotivation...',
                    'properties' => 'MyOneSolutionIsMyQueen',
                    'deviceTypeId' => $deviceTypeId,
                ],
                'relationships' => [
                    'assets' => [
                        'data' => [
                            ['type' => 'assets', 'aa' => '1'],
                            ['type' => 'assets', 'aa' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'devices',
            'name' => 'whenIneedMotivation...',
            'properties' => 'MyOneSolutionIsMyQueen',
        ]);
    }

    public function testUpdateDevice()
    {
        $device = factory(\WA\DataStore\Device\Device::class)->create(
            ['properties' => 'properties1', 'name' => 'Phone1']
        );
        $deviceAux = factory(\WA\DataStore\Device\Device::class)->create(
            ['properties' => 'properties2', 'name' => 'Samsung2']
        );

        $this->assertNotEquals($device->id, $deviceAux->id);
        $this->assertNotEquals($device->identification, $deviceAux->identification);
        $this->assertNotEquals($device->name, $deviceAux->name);
        $this->assertNotEquals($device->properties, $deviceAux->properties);

        $modCap1 = factory(\WA\DataStore\Modification\Modification::class)->create(
            ['modType' => 'capacity']
        );

        $modSty1 = factory(\WA\DataStore\Modification\Modification::class)->create(
            ['modType' => 'style']
        );

        $modCap2 = factory(\WA\DataStore\Modification\Modification::class)->create(
            ['modType' => 'capacity']
        );

        $price1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'carrierId' => 1,
                'companyId' => 1,
                'priceRetail' => 100,
                'price1' => 100,
                'price2' => 100,
                'priceOwn' => 100, ]
        );

        $price2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'carrierId' => 1,
                'companyId' => 2,
                'priceRetail' => 200,
                'price1' => 200,
                'price2' => 200,
                'priceOwn' => 200, ]
        );

        $price3 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'carrierId' => 2,
                'companyId' => 1,
                'priceRetail' => 300,
                'price1' => 300,
                'price2' => 300,
                'priceOwn' => 300, ]
        );

        $price4 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'carrierId' => 2,
                'companyId' => 2,
                'priceRetail' => 400,
                'price1' => 400,
                'price2' => 400,
                'priceOwn' => 400, ]
        );

        $price5 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'carrierId' => 1,
                'companyId' => 1,
                'priceRetail' => 500,
                'price1' => 500,
                'price2' => 500,
                'priceOwn' => 500, ]
        );

        $price6 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'carrierId' => 1,
                'companyId' => 2,
                'priceRetail' => 600,
                'price1' => 600,
                'price2' => 600,
                'priceOwn' => 600, ]
        );

        $price7 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'carrierId' => 2,
                'companyId' => 1,
                'priceRetail' => 700,
                'price1' => 700,
                'price2' => 700,
                'priceOwn' => 700, ]
        );

        $price8 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'carrierId' => 2,
                'companyId' => 2,
                'priceRetail' => 800,
                'price1' => 800,
                'price2' => 800,
                'priceOwn' => 800, ]
        );

        $res = $this->PATCH('/devices/'.$device->id.'?include=devicetypes,modifications,devicevariations,devicevariations.carriers,devicevariations.companies,devicevariations.devices',
            [
                'data' => [
                    'type' => 'devices',
                    'attributes' => [
                        'name' => $deviceAux->name,
                        'properties' => $deviceAux->properties,
                    ],
                    'relationships' => [
                        'modifications' => [
                            'data' => [
                                ['type' => 'modifications', 'id' => $modCap1->id],
                                ['type' => 'modifications', 'id' => $modSty1->id],
                                ['type' => 'modifications', 'id' => $modCap2->id],
                            ],
                        ],
                        'devicevariations' => [
                            'data' => [
                                [
                                    'type' => 'devicevariations',
                                    'id' => $price1->id,
                                    'attributes' => [
                                        'carrierId' => 1,
                                        'deviceId'=> $device->id,
                                        'companyId' => 1,
                                        'priceRetail' => 1100,
                                        'price1' => 1100,
                                        'price2' => 1100,
                                        'priceOwn' => 1100
                                    ]
                                ],
                                [
                                    'type' => 'devicevariations',
                                    'id' => $price2->id,
                                    'attributes' => [
                                        'carrierId' => 1,
                                        'deviceId'=> $device->id,
                                        'companyId' => 2,
                                        'priceRetail' => 1200,
                                        'price1' => 1200,
                                        'price2' => 1200,
                                        'priceOwn' => 1200
                                    ]
                                ],
                                [
                                    'type' => 'devicevariations',
                                    'id' => $price3->id,
                                    'attributes' => [
                                        'carrierId' => 2,
                                        'deviceId'=> $device->id,
                                        'companyId' => 1,
                                        'priceRetail' => 1300,
                                        'price1' => 1300,
                                        'price2' => 1300,
                                        'priceOwn' => 1300
                                    ]
                                ],
                                [
                                    'type' => 'devicevariations',
                                    'id' => $price4->id,
                                    'attributes' => [
                                        'carrierId' => 2,
                                        'deviceId'=> $device->id,
                                        'companyId' => 2,
                                        'priceRetail' => 1400,
                                        'price1' => 1400,
                                        'price2' => 1400,
                                        'priceOwn' => 1400
                                    ]
                                ],
                                [
                                    'type' => 'devicevariations',
                                    'id' => $price5->id,
                                    'attributes' => [
                                        'carrierId' => 1,
                                        'deviceId'=> $device->id,
                                        'companyId' => 1,
                                        'priceRetail' => 1500,
                                        'price1' => 1500,
                                        'price2' => 1500,
                                        'priceOwn' => 1500
                                    ]
                                ],
                                [
                                    'type' => 'devicevariations',
                                    'id' => $price6->id,
                                    'attributes' => [
                                        'carrierId' => 1,
                                        'deviceId'=> $device->id,
                                        'companyId' => 2,
                                        'priceRetail' => 1600,
                                        'price1' => 1600,
                                        'price2' => 1600,
                                        'priceOwn' => 1600
                                    ]
                                ],
                                [
                                    'type' => 'devicevariations',
                                    'id' => $price7->id,
                                    'attributes' => [
                                        'carrierId' => 2,
                                        'deviceId'=> $device->id,
                                        'companyId' => 1,
                                        'priceRetail' => 1700,
                                        'price1' => 1700,
                                        'price2' => 1700,
                                        'priceOwn' => 1700
                                    ]
                                ],
                                [
                                    'type' => 'devicevariations',
                                    'id' => $price8->id,
                                    'attributes' => [
                                        'carrierId' => 2,
                                        'deviceId'=> $device->id,
                                        'companyId' => 2,
                                        'priceRetail' => 1800,
                                        'price1' => 1800,
                                        'price2' => 1800,
                                        'priceOwn' => 1800
                                    ]
                                ]
                            ],
                        ],
                    ],
                ],
            ])
            //Log::debug("testGetPackageByIdandIncludesDevices: ".print_r($res->response->getContent(), true));
            ->seeJson(
            [
                'type' => 'devices',
                'name' => $deviceAux->name,
                'properties' => $deviceAux->properties,
            ])
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'identification',
                        'name',
                        'properties',
                        'externalId',
                        'statusId',
                        'syncId',
                        'make',
                        'model',
                        'defaultPrice',
                        'currency',
                        'created_at' => [
                            'date',
                            'timezone_type',
                            'timezone'
                        ],
                        'updated_at' => [
                            'date',
                            'timezone_type',
                            'timezone'
                        ]
                    ],
                    'links' => [
                        'self'
                    ],
                    'relationships' => [
                        'devicetypes' => [
                            'links' => [
                                'self',
                                'related'
                            ],
                            'data' => []
                        ],
                        'modifications' => [
                            'links' => [
                                'self',
                                'related'
                            ],
                            'data' => [
                                0 => [
                                    'type',
                                    'id'
                                ],
                                1 => [
                                    'type',
                                    'id'
                                ],
                                2 => [
                                    'type',
                                    'id'
                                ]
                            ]
                        ],
                        'devicevariations' => [
                            'links' => [
                                'self',
                                'related',
                            ],
                            'data' => [
                                0 => [
                                    'type',
                                    'id'
                                ],
                                1 => [
                                    'type',
                                    'id'
                                ],
                                2 => [
                                    'type',
                                    'id'
                                ],
                                3 => [
                                    'type',
                                    'id'
                                ],
                                4 => [
                                    'type',
                                    'id'
                                ],
                                5 => [
                                    'type',
                                    'id'
                                ],
                                6 => [
                                    'type',
                                    'id'
                                ],
                                7 => [
                                    'type',
                                    'id'
                                ]
                            ]
                        ]
                    ]
                ],
                'included' => [
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'modType',
                            'value',
                            'unit',
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    1 => [
                        'type',
                        'id',
                        'attributes' => [
                            'modType',
                            'value',
                            'unit',
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    2 => [
                        'type',
                        'id',
                        'attributes' => [
                            'modType',
                            'value',
                            'unit',
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    3 => [
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
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    4 => [
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
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    5 => [
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
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    6 => [
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
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    7 => [
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
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    8 => [
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
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    9 => [
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
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    10 => [
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
                        ],
                        'links' => [
                            'self',
                        ],
                    ]
                ],
            ]);
    }

    public function testUpdateServiceIncludeDeviceVariations(){

        $device = factory(\WA\DataStore\Device\Device::class)->create(
            ['properties' => 'properties1', 'name' => 'Phone1']
        );

        $modCap1 = factory(\WA\DataStore\Modification\Modification::class)->create(
            ['modType' => 'capacity']
        );

        $modSty1 = factory(\WA\DataStore\Modification\Modification::class)->create(
            ['modType' => 'style']
        );

        $modCap2 = factory(\WA\DataStore\Modification\Modification::class)->create(
            ['modType' => 'capacity']
        );

        $deviceVariation1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'deviceId' => $device->id,
                'carrierId' => 1,
                'companyId' => 1,
                'priceRetail' => 100,
                'price1' => 100,
                'price2' => 100,
                'priceOwn' => 100, ]
        );

        $deviceVariation2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'deviceId' => $device->id,
                'carrierId' => 1,
                'companyId' => 2,
                'priceRetail' => 200,
                'price1' => 200,
                'price2' => 200,
                'priceOwn' => 200, ]
        );

        $deviceVariation3 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'deviceId' => $device->id,
                'carrierId' => 2,
                'companyId' => 1,
                'priceRetail' => 300,
                'price1' => 300,
                'price2' => 300,
                'priceOwn' => 300, ]
        );

        $deviceVariation4 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'deviceId' => $device->id,
                'carrierId' => 2,
                'companyId' => 2,
                'priceRetail' => 400,
                'price1' => 400,
                'price2' => 400,
                'priceOwn' => 400, ]
        );


        $res = $this->PATCH('/devices/'.$device->id.'?include=devicetypes,devicevariations',
            [
                'data' => [
                    'type' => 'devices',
                    'attributes' => [
                        'name' => $device->name,
                        'properties' => $device->properties,
                    ],
                    'relationships' => [
                        'modifications' => [
                            'data' => [
                                ['type' => 'modifications', 'id' => $modCap1->id],
                                ['type' => 'modifications', 'id' => $modSty1->id],
                            ],
                        ],
                        'devicevariations' => [
                            'data' => [
                                [
                                    'type' => 'devicevariations',
                                    'id' => $deviceVariation1->id,
                                    'attributes' => [
                                        'carrierId' => 1,
                                        'deviceId'=> $device->id,
                                        'companyId' => 1,
                                        'priceRetail' => 1500,
                                        'price1' => 1500,
                                        'price2' => 1500,
                                        'priceOwn' => 1500
                                    ]
                                ],
                                [
                                    'type' => 'devicevariations',
                                    'id' => $deviceVariation2->id,
                                    'attributes' => [
                                        'carrierId' => 1,
                                        'deviceId'=> $device->id,
                                        'companyId' => 2,
                                        'priceRetail' => 1600,
                                        'price1' => 1600,
                                        'price2' => 1600,
                                        'priceOwn' => 1600
                                    ]
                                ],
                                [
                                    'type' => 'devicevariations',
                                    'id' => $deviceVariation3->id,
                                    'attributes' => [
                                        'carrierId' => 2,
                                        'deviceId'=> $device->id,
                                        'companyId' => 1,
                                        'priceRetail' => 1700,
                                        'price1' => 1700,
                                        'price2' => 1700,
                                        'priceOwn' => 1700
                                    ]
                                ],
                                [
                                    'type' => 'devicevariations',
                                    'id' => $deviceVariation4->id,
                                    'attributes' => [
                                        'carrierId' => 2,
                                        'deviceId'=> $device->id,
                                        'companyId' => 2,
                                        'priceRetail' => 1800,
                                        'price1' => 1800,
                                        'price2' => 1800,
                                        'priceOwn' => 1800
                                    ]
                                ]
                            ],
                        ],
                    ],
                ],
            ])
            //Log::debug("testGetPackageByIdandIncludesDevices: ".print_r($res->response->getContent(), true));
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'identification',
                        'name',
                        'properties',
                        'externalId',
                        'statusId',
                        'syncId',
                        'created_at',
                        'updated_at',
                    ],
                    'links' => [
                        'self'
                    ],
                    'relationships' => [
                        'devicetypes' => [
                            'links' => [
                                'self',
                                'related',
                            ],
                            'data' => []
                        ],
                        'devicevariations' => [
                            'links' => [
                                'self',
                                'related',
                            ],
                            'data' => [
                                [
                                    'type',
                                    'id',
                                ],
                                [
                                    'type',
                                    'id',
                                ],
                                [
                                    'type',
                                    'id',
                                ],
                                [
                                    'type',
                                    'id',
                                ]
                            ]
                        ]
                    ]
                ],
                'included' => [
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
                            'companyId'
                        ],
                        'links' => [
                            'self'
                        ]
                    ],
                    1 => [
                        'type',
                        'id',
                        'attributes' => [
                            'priceRetail',
                            'price1',
                            'price2',
                            'priceOwn',
                            'deviceId',
                            'carrierId',
                            'companyId'
                        ],
                        'links' => [
                            'self'
                        ]
                    ],
                    2 => [
                        'type',
                        'id',
                        'attributes' => [
                            'priceRetail',
                            'price1',
                            'price2',
                            'priceOwn',
                            'deviceId',
                            'carrierId',
                            'companyId'
                        ],
                        'links' => [
                            'self'
                        ]
                    ],
                    3 => [
                        'type',
                        'id',
                        'attributes' => [
                            'priceRetail',
                            'price1',
                            'price2',
                            'priceOwn',
                            'deviceId',
                            'carrierId',
                            'companyId'
                        ],
                        'links' => [
                            'self'
                        ]
                    ]
                ]
            ]);
    }

    public function testDeleteDeviceIfExists()
    {
        // CREATE & DELETE
        $device = factory(\WA\DataStore\Device\Device::class)->create();
        $responseDel = $this->call('DELETE', '/devices/'.$device->id);
        //dd($responseDel);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', '/devices/'.$device->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeleteDeviceIfNoExists()
    {
        // DELETE NO EXISTING.
        $responseDel = $this->call('DELETE', '/devices/1');
        $this->assertEquals(404, $responseDel->status());
    }

    public function testParseJsonToArray()
    {
        $array = array();
        $type = 'anytype';

        for ($i = 1; $i < 5; ++$i) {
            $arrayAux = array('type' => $type, 'id' => $i);
            array_push($array, $arrayAux);
        }

        $devicesController = app()->make('WA\Http\Controllers\DevicesController');
        $reflection = new \ReflectionClass($devicesController);
        $method = $reflection->getMethod('parseJsonToArray');
        $method->setAccessible(true);

        $value = $method->invokeArgs($devicesController, array($array, $type));
        $real = array(1, 2, 3, 4);
        $this->assertSame($value, $real);
    }

    public function testParseJsonToArrayReturnVoidNoType()
    {
        $array = array();
        $type = 'anytype';

        for ($i = 1; $i < 5; ++$i) {
            $arrayAux = array('error' => $type, 'id' => $i);
            array_push($array, $arrayAux);
        }

        $devicesController = app()->make('WA\Http\Controllers\DevicesController');
        $reflection = new \ReflectionClass($devicesController);
        $method = $reflection->getMethod('parseJsonToArray');
        $method->setAccessible(true);

        $value = $method->invokeArgs($devicesController, array($array, $type));
        $real = array();
        $this->assertSame($value, $real);
    }

    public function testParseJsonToArrayReturnVoidNoSameType()
    {
        $array = array();
        $type = 'anytype';

        for ($i = 1; $i < 5; ++$i) {
            $arrayAux = array('type' => 'error', 'id' => $i);
            array_push($array, $arrayAux);
        }

        $devicesController = app()->make('WA\Http\Controllers\DevicesController');
        $reflection = new \ReflectionClass($devicesController);
        $method = $reflection->getMethod('parseJsonToArray');
        $method->setAccessible(true);

        $value = $method->invokeArgs($devicesController, array($array, $type));
        $real = array();
        $this->assertSame($value, $real);
    }

    public function testParseJsonToArrayReturnVoidNoId()
    {
        $array = array();
        $type = 'anytype';

        for ($i = 1; $i < 5; ++$i) {
            $arrayAux = array('type' => $type, 'error' => $i);
            array_push($array, $arrayAux);
        }

        $devicesController = app()->make('WA\Http\Controllers\DevicesController');
        $reflection = new \ReflectionClass($devicesController);
        $method = $reflection->getMethod('parseJsonToArray');
        $method->setAccessible(true);

        $value = $method->invokeArgs($devicesController, array($array, $type));
        $real = array();
        $this->assertSame($value, $real);
    }

    public function testDeleteRepeat()
    {
        $start = array(
            ['type' => 'prices', 'carrierId' => 1, 'companyId' => 1, 'priceRetail' => 100, 'price1' => 100, 'price2' => 100, 'priceOwn' => 100],
            ['type' => 'prices', 'carrierId' => 1, 'companyId' => 1, 'priceRetail' => 200, 'price1' => 200, 'price2' => 200, 'priceOwn' => 200],
            ['type' => 'prices', 'carrierId' => 2, 'companyId' => 1, 'priceRetail' => 300, 'price1' => 300, 'price2' => 300, 'priceOwn' => 300],
            ['type' => 'prices', 'carrierId' => 2, 'companyId' => 1, 'priceRetail' => 400, 'price1' => 400, 'price2' => 400, 'priceOwn' => 400],
        );

        $final = array(
            ['type' => 'prices', 'carrierId' => 1, 'companyId' => 1, 'priceRetail' => 100, 'price1' => 100, 'price2' => 100, 'priceOwn' => 100],
            ['type' => 'prices', 'carrierId' => 2, 'companyId' => 1, 'priceRetail' => 300, 'price1' => 300, 'price2' => 300, 'priceOwn' => 300],
        );

        $devicesController = app()->make('WA\Http\Controllers\DevicesController');
        $reflection = new \ReflectionClass($devicesController);
        $method = $reflection->getMethod('deleteRepeat');
        $method->setAccessible(true);

        $result = $method->invokeArgs($devicesController, array($start));
        $this->assertSame($result, $final);
    }

    public function testDeleteRepeatDoingNothing()
    {
        $start = array(
            ['type' => 'prices', 'carrierId' => 1, 'companyId' => 1, 'priceRetail' => 100, 'price1' => 100, 'price2' => 100, 'priceOwn' => 100],
            ['type' => 'prices', 'carrierId' => 2, 'companyId' => 1, 'priceRetail' => 300, 'price1' => 300, 'price2' => 300, 'priceOwn' => 300],
        );

        $final = array(
            ['type' => 'prices', 'carrierId' => 1, 'companyId' => 1, 'priceRetail' => 100, 'price1' => 100, 'price2' => 100, 'priceOwn' => 100],
            ['type' => 'prices', 'carrierId' => 2, 'companyId' => 1, 'priceRetail' => 300, 'price1' => 300, 'price2' => 300, 'priceOwn' => 300],
        );

        $devicesController = app()->make('WA\Http\Controllers\DevicesController');
        $reflection = new \ReflectionClass($devicesController);
        $method = $reflection->getMethod('deleteRepeat');
        $method->setAccessible(true);

        $result = $method->invokeArgs($devicesController, array($start));
        $this->assertSame($result, $final);
    }

    /*public function testCheckIfPriceRowIsCorrect()
    {
        $modCap1 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;
        $modSty2 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'style'])->id;
        $modCap3 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;
        $modSty4 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'style'])->id;
        $modCap5 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;
        $modSty6 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'style'])->id;

        $carr1 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;
        $carr2 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;
        $carr3 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;

        $comp1 = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $comp2 = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $comp3 = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $price = array(
            'type' => 'prices', 'carrierId' => $carr1, 'companyId' => $comp1, 'priceRetail' => 100, 'price1' => 100, 'price2' => 100, 'priceOwn' => 100,
        );
        $modifications = array($modCap1, $modSty2, $modCap3, $modSty4, $modCap5, $modSty6);
        $carriers = array($carr1, $carr2, $carr3);
        $companies = array($comp1, $comp2, $comp3);

        $devicesController = app()->make('WA\Http\Controllers\DevicesController');
        $reflection = new \ReflectionClass($devicesController);
        $method = $reflection->getMethod('checkIfPriceRowIsCorrect');
        $method->setAccessible(true);

        $result = $method->invokeArgs($devicesController, array($price, $modifications, $carriers, $companies));
        $final = array('bool' => true, 'error' => 'No Error', 'id' => 0);
        $this->assertSame($result, $final);
    }

    public function testCheckIfPriceRowIsCorrectCarrierFails()
    {
        $modCap1 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;
        $modSty2 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'style'])->id;
        $modCap3 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;
        $modSty4 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'style'])->id;
        $modCap5 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;
        $modSty6 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'style'])->id;

        $carr1 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;
        $carr2 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;
        $carr3 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;

        $comp1 = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $comp2 = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $comp3 = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $price = array(
            'type' => 'prices', 'carrierId' => $carr1, 'companyId' => $comp1, 'priceRetail' => 100, 'price1' => 100, 'price2' => 100, 'priceOwn' => 100,
        );
        $modifications = array($modCap1, $modSty2, $modCap3, $modSty4, $modCap5, $modSty6);
        $carriers = array($carr2, $carr3);
        $companies = array($comp1, $comp2, $comp3);

        $devicesController = app()->make('WA\Http\Controllers\DevicesController');
        $reflection = new \ReflectionClass($devicesController);
        $method = $reflection->getMethod('checkIfPriceRowIsCorrect');
        $method->setAccessible(true);

        $result = $method->invokeArgs($devicesController, array($price, $modifications, $carriers, $companies));
        $final = array('bool' => false, 'error' => 'Carrier Not Found', 'id' => 1);
        $this->assertSame($result, $final);
    }

    public function testCheckIfPriceRowIsCorrectCompanyFails()
    {
        $modCap1 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;
        $modSty2 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'style'])->id;
        $modCap3 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;
        $modSty4 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'style'])->id;
        $modCap5 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'capacity'])->id;
        $modSty6 = factory(\WA\DataStore\Modification\Modification::class)->create(['modType' => 'style'])->id;

        $carr1 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;
        $carr2 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;
        $carr3 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;

        $comp1 = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $comp2 = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $comp3 = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $price = array(
            'type' => 'prices', 'carrierId' => $carr1, 'companyId' => $comp1, 'priceRetail' => 100, 'price1' => 100, 'price2' => 100, 'priceOwn' => 100,
        );
        $modifications = array($modCap1, $modSty2, $modCap3, $modSty4, $modCap5, $modSty6);
        $carriers = array($carr1, $carr2, $carr3);
        $companies = array($comp2, $comp3);

        $devicesController = app()->make('WA\Http\Controllers\DevicesController');
        $reflection = new \ReflectionClass($devicesController);
        $method = $reflection->getMethod('checkIfPriceRowIsCorrect');
        $method->setAccessible(true);

        $result = $method->invokeArgs($devicesController, array($price, $modifications, $carriers, $companies));
        $final = array('bool' => false, 'error' => 'Company Not Found', 'id' => 1);
        $this->assertSame($result, $final);
    }*/
}
