<?php

//use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\DatabaseMigrations;

class DeviceVariationApiTest extends TestCase
{
    //use DatabaseTransactions;
    use DatabaseMigrations;

    /**
     * A basic functional test for Prices.
     */
    public function testGetDeviceVariations()
    {
        factory(\WA\DataStore\DeviceVariation\DeviceVariation::class, 40)->create();

        $res = $this->json('GET', 'devicevariations');

        $res->seeJsonStructure([
            'data' => [
                0 => [
                    'type',
                    'id',
                    'attributes' => [
                        'deviceId',
                        'carrierId',
                        'companyId',
                        'priceRetail',
                        'price1',
                        'price2',
                        'priceOwn',
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

    public function testGetDeviceVariationById()
    {
        $devVar = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create();

        $res = $this->json('GET', 'devicevariations/'.$devVar->id)
            ->seeJson(
            [
                'type' => 'devicevariations',
                'deviceId' => $devVar->deviceId,
                'carrierId' => $devVar->carrierId,
                'companyId' => $devVar->companyId,
                'priceRetail' => $devVar->priceRetail,
                'price1' => $devVar->price1,
                'price2' => $devVar->price2,
                'priceOwn' => $devVar->priceOwn,
            ]);
    }

    public function testCreateDeviceVariation()
    {
        $deviceVariation = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create();
        $device = factory(\WA\DataStore\Device\Device::class)->create();
        $modCap1 = factory(\WA\DataStore\Modification\Modification::class)->create()->id;
        $carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        $company = factory(\WA\DataStore\Company\Company::class)->create();
        $image1 = factory(\WA\DataStore\Image\Image::class)->create()->id;
        $image2 = factory(\WA\DataStore\Image\Image::class)->create()->id;

       $deviceVariation = $this->POST('/devicevariations',
            [
                'data' => [
                    'type' => 'devicevariations',
                    'attributes' => [
                        'deviceId' => $device->id,
                        'carrierId' => $carrier->id,
                        'companyId' => $company->id,
                        'priceRetail' => 300,
                        'price1' => 400,
                        'price2' => 500,
                        'priceOwn' => 600,
                    ],
                    'relationships' => [
                        'images' => [
                            'data' => [
                                ['type' => 'images', 'id' => $image1],
                                ['type' => 'images', 'id' => $image2],
                            ],
                        ],
                        'modifications' => [
                            'data' => [
                                ['type' => 'modifications', 'id' => $modCap1],
                            ],
                        ],
                    ]
                ]
            ])
            ->seeJson([
                'type' => 'devicevariations',
                'deviceId' => $device->id,
                'carrierId' => $carrier->id,
                'companyId' => $company->id,
                'priceRetail' => 300,
                'price1' => 400,
                'price2' => 500,
                'priceOwn' => 600,
            ]);
    }

    public function testUpdateDeviceVariation()
    {  
        $deviceVariation = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create();
        $device1 = factory(\WA\DataStore\Device\Device::class)->create()->id;
        $modCap1 = factory(\WA\DataStore\Modification\Modification::class)->create()->id;
        
        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;
        $company1 = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $device2 = factory(\WA\DataStore\Device\Device::class)->create()->id;
        
        $carrier2 = factory(\WA\DataStore\Carrier\Carrier::class)->create()->id;
        $company2 = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $devVar = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'deviceId' => $device1,
                'carrierId' => $carrier1,
                'companyId' => $company1,
                'priceRetail' => 300,
                'price1' => 400,
                'price2' => 500,
                'priceOwn' => 600,
            ]
        );

        $devVarAux = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create(
            [
                'deviceId' => $device2,
                'carrierId' => $carrier2,
                'companyId' => $company2,
                'priceRetail' => 350,
                'price1' => 450,
                'price2' => 550,
                'priceOwn' => 650,
            ]
        );

        $this->assertNotEquals($devVar->id, $devVarAux->id);
        $this->assertNotEquals($devVar->deviceId, $devVarAux->deviceId);
        $this->assertNotEquals($devVar->carrierId, $devVarAux->carrierId);
        $this->assertNotEquals($devVar->companyId, $devVarAux->companyId);
        $this->assertNotEquals($devVar->priceRetail, $devVarAux->priceRetail);
        $this->assertNotEquals($devVar->price1, $devVarAux->price1);
        $this->assertNotEquals($devVar->price2, $devVarAux->price2);
        $this->assertNotEquals($devVar->priceOwn, $devVarAux->priceOwn);

        $deviceVariation = $this->PATCH('/devicevariations/'.$devVarAux->id,
            [
                'data' => [
                    'type' => 'devicevariations',
                    'attributes' => [
                        'deviceId' => $devVar->deviceId,
                        'carrierId' => $devVar->carrierId,
                        'companyId' => $devVar->companyId,
                        'priceRetail' => $devVar->priceRetail,
                        'price1' => $devVar->price1,
                        'price2' => $devVar->price2,
                        'priceOwn' => $devVar->priceOwn,
                    ],
                    'relationships' => [
                        'modifications' => [
                            'data' => [
                                ['type' => 'modifications', 'id' => $modCap1],
                            ],
                        ],
                    ]
                ],
            ])
            ->seeJson([
                'type' => 'devicevariations',
                'deviceId' => $devVar->deviceId,
                'carrierId' => $devVar->carrierId,
                'companyId' => $devVar->companyId,
                'priceRetail' => $devVar->priceRetail,
                'price1' => $devVar->price1,
                'price2' => $devVar->price2,
                'priceOwn' => $devVar->priceOwn,
            ]);
    }

    public function testDeleteDeviceVariationIfExists()
    {
        // CREATE & DELETE
        $devVar = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create();
        $responseDel = $this->call('DELETE', '/devicevariations/'.$devVar->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', '/devicevariations/'.$devVar->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeleteDeviceVariationIfNoExists()
    {
        // DELETE NO EXISTING.
        $responseDel = $this->call('DELETE', '/devicevariations/1');
        $this->assertEquals(404, $responseDel->status());
    }
}
