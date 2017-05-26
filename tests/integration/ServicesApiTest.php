<?php

//use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\DatabaseMigrations;

use WA\DataStore\ServiceItem\ServiceItem;



class ServicesApiTest extends TestCase
{
    //use DatabaseTransactions;
    use DatabaseMigrations;

    /**
     * A basic functional test for services.
     */

    public function testGetServices()
    {
        factory(\WA\DataStore\Service\Service::class, 40)->create();

        $res = $this->json('GET', 'services')->seeJsonStructure([
            'data' => [
                0 => [
                    'type',
                    'id',
                    'attributes' => [
                        'status',
                        'title',
                        'planCode',
                        'cost',
                        'description',
                        'currency',
                        'carrierId',
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

    public function testGetServiceByIdandIncludes()
    {
        $carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();

        $service = factory(\WA\DataStore\Service\Service::class)->create(['carrierId' => $carrier->id]);
        
        $package = factory(\WA\DataStore\Package\Package::class)->create();
        $service->packages()->sync([$package->id]);

        factory(\WA\DataStore\ServiceItem\ServiceItem::class)->create(['serviceId' => $service->id]);
        factory(\WA\DataStore\ServiceItem\ServiceItem::class)->create(['serviceId' => $service->id]);

        $res = $this->json('GET', 'services/'.$service->id.'?include=serviceitems,carriers,packages')
        //Log::debug("testGetServiceByIdandIncludes: ".print_r($res->response->getContent(), true));
            ->seeJson([
                'status' => $service->status,
                'title' => $service->title,
                'planCode' => "$service->planCode",
                'cost' => "$service->cost",
                'description' => $service->description,
                'currency' => $service->currency,
                'carrierId' => $service->carrierId,
            ])
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'status',
                        'title',
                        'planCode',
                        'cost',
                        'description',
                        'currency',
                        'carrierId',
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
                        'serviceitems' => [
                            'links' => [
                                'self',
                                'related',
                            ],
                            'data' => [
                                0 => [
                                    'type',
                                    'id',
                                ],
                                1 => [
                                    'type',
                                    'id',
                                ]
                            ],
                        ],
                        'carriers' => [
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
                        'packages' => [
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
                        ]
                    ],
                ],
                'included' => [
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'presentation',
                            'active',
                            'locationId',
                            'shortName'
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    1 => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'information',
                            'companyId'
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    2 => [
                        'type',
                        'id',
                        'attributes' => [
                            'category',
                            'description',
                            'value',
                            'unit',
                            'cost',
                            'domain',
                            'serviceId'
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    3 => [
                        'type',
                        'id',
                        'attributes' => [
                            'category',
                            'description',
                            'value',
                            'unit',
                            'cost',
                            'domain',
                            'serviceId'
                        ],
                        'links' => [
                            'self',
                        ],
                    ]

                ],
            ]);
    }

    public function testCreateService()
    {
        $carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        $package = factory(\WA\DataStore\Package\Package::class)->create();
        $serviceitem1 = factory(\WA\DataStore\ServiceItem\ServiceItem::class)->create();
        $serviceitem2 = factory(\WA\DataStore\ServiceItem\ServiceItem::class)->create();

        $res = $this->json('POST', 'services?include=serviceitems,carriers,packages',
                [
                    'data' => [
                        'type' => 'services',
                        'attributes' => [
                            'status' => 'Enabled',
                            'title' => 'Service Test',
                            'planCode' => '11111',
                            'cost' => '22',
                            'description' => 'Test Service',
                            'currency' => 'USD',
                            'carrierId' => $carrier->id,
                        ],
                        'relationships' => [
                            'packages' => [
                                'data' => [
                                    ['type' => 'packages', 'id' => $package->id]
                                ],
                            ],
                            'serviceitems' => [
                                'data' => [
                                    [
                                        'category' => $serviceitem1->category,
                                        'description' => $serviceitem1->description,
                                        'value' => $serviceitem1->value,
                                        'unit' => $serviceitem1->unit,
                                        'cost' => $serviceitem1->cost,
                                        'domain' => $serviceitem1->domain,
                                    ],
                                    [
                                        'category' => $serviceitem2->category,
                                        'description' => $serviceitem2->description,
                                        'value' => $serviceitem2->value,
                                        'unit' => $serviceitem2->unit,
                                        'cost' => $serviceitem2->cost,
                                        'domain' => $serviceitem2->domain,
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            )
            //Log::debug("testCreateService: ".print_r($res->response->getContent(), true));
            ->seeJson([
                'status' => 'Enabled',
                'title' => 'Service Test',
                'planCode' => '11111',
                'cost' => '22',
                'description' => 'Test Service',
                'currency' => 'USD',
                'carrierId' => 1,
            ])
            ->seeJsonStructure([                
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'status',
                        'title',
                        'planCode',
                        'cost',
                        'description',
                        'currency',
                        'carrierId',
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
                        'serviceitems' => [
                            'links' => [
                                'self',
                                'related',
                            ],
                            'data' => [
                                0 => [
                                    'type',
                                    'id',
                                ],
                                1 => [
                                    'type',
                                    'id',
                                ]
                            ],
                        ],
                        'carriers' => [
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
                        'packages' => [
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
                        ]
                    ],
                ],
                'included' => [
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'presentation',
                            'active',
                            'locationId',
                            'shortName'
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    1 => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'information',
                            'companyId'
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    2 => [
                        'type',
                        'id',
                        'attributes' => [
                            'category',
                            'description',
                            'value',
                            'unit',
                            'cost',
                            'domain',
                            'serviceId'
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    3 => [
                        'type',
                        'id',
                        'attributes' => [
                            'category',
                            'description',
                            'value',
                            'unit',
                            'cost',
                            'domain',
                            'serviceId'
                        ],
                        'links' => [
                            'self',
                        ],
                    ]

                ],
            ]);
    }

    public function testUpdateService()
    {
        $service = factory(\WA\DataStore\Service\Service::class)->create(
            ['status' => 'Enabled', 'title' => 'title1', 'planCode' => 11111, 'cost' => 30, 'description' => 'desc1', 'currency' => 'USD', 'carrierId' => 1]
        );
        $serviceAux = factory(\WA\DataStore\Service\Service::class)->create(
            ['status' => 'Disabled', 'title' => 'title2', 'planCode' => 22222, 'cost' => 40, 'description' => 'desc2', 'currency' => 'EUR', 'carrierId' => 2]
        );

        $this->assertNotEquals($service->status, $serviceAux->status);
        $this->assertNotEquals($service->id, $serviceAux->id);
        $this->assertNotEquals($service->title, $serviceAux->title);
        $this->assertNotEquals($service->cost, $serviceAux->cost);
        $this->assertNotEquals($service->description, $serviceAux->description);
        $this->assertNotEquals($service->currency, $serviceAux->currency);
        $this->assertNotEquals($service->carrierId, $serviceAux->carrierId);

        $this->json('PATCH', '/services/'.$serviceAux->id,
            [
                'data' => [
                    'type' => 'services',
                    'id' => $service->id,
                    'attributes' => [
                        'status' => $service->status,
                        'title' => "$service->title",
                        'planCode' => "$service->planCode",
                        'cost' => "$service->cost",
                        'description' => $service->description,
                        'currency' => $service->currency,
                        'carrierId' => $service->carrierId,
                    ],
                ],
            ])
            ->seeJson([
                'status' => $service->status,
                'title' => "$service->title",
                'planCode' => "$service->planCode",
                'cost' => "$service->cost",
                'description' => $service->description,
                'currency' => $service->currency,
                'carrierId' => $service->carrierId
            ]);
    }

    public function testUpdateServiceIncludeAllDeleteRelationships()
    {
        $carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();

        $service = factory(\WA\DataStore\Service\Service::class)->create(['carrierId' => $carrier->id]);
 
        // SERVICEITEMS
        $serviceitem1 = factory(\WA\DataStore\ServiceItem\ServiceItem::class)->create(['serviceId' => $service->id]);
        $serviceitem2 = factory(\WA\DataStore\ServiceItem\ServiceItem::class)->create(['serviceId' => $service->id]);

        $serviceSItDB = DB::table('service_items')->where('serviceId', $service->id)->get();

        $this->assertCount(2, $serviceSItDB);
        $this->assertEquals($serviceSItDB[0]->serviceId, $service->id);
        $this->assertEquals($serviceSItDB[1]->serviceId, $service->id);

        $serviceitem1DB = DB::table('service_items')->where('serviceId', $service->id)->get()[0];
        $serviceitem2DB = DB::table('service_items')->where('serviceId', $service->id)->get()[1];

        $this->assertEquals($serviceitem1DB->id, $serviceitem1->id);
        $this->assertEquals($serviceitem1DB->serviceId, $service->id);
        $this->assertEquals($serviceitem1DB->category, $serviceitem1->category);
        $this->assertEquals($serviceitem1DB->value, $serviceitem1->value);
        $this->assertEquals($serviceitem1DB->description, $serviceitem1->description);
        $this->assertEquals($serviceitem1DB->cost, $serviceitem1->cost);
        $this->assertEquals($serviceitem1DB->unit, $serviceitem1->unit);
        $this->assertEquals($serviceitem1DB->domain, $serviceitem1->domain);

        $this->assertEquals($serviceitem2DB->id, $serviceitem2->id);
        $this->assertEquals($serviceitem2DB->serviceId, $service->id);
        $this->assertEquals($serviceitem2DB->category, $serviceitem2->category);
        $this->assertEquals($serviceitem2DB->value, $serviceitem2->value);
        $this->assertEquals($serviceitem2DB->description, $serviceitem2->description);
        $this->assertEquals($serviceitem2DB->cost, $serviceitem2->cost);
        $this->assertEquals($serviceitem2DB->unit, $serviceitem2->unit);
        $this->assertEquals($serviceitem2DB->domain, $serviceitem2->domain);

        $res = $this->json('PATCH', '/services/'.$service->id.'?include=serviceitems',
            [
                'data' => [
                    'type' => 'services',
                    'attributes' => [
                        'status' => $service->status,
                        'title' => $service->title,
                        'planCode' => $service->planCode,
                        'cost' => $service->cost,
                        'description' => $service->description,
                        'currency' => $service->currency,
                        'carrierId' => $service->carrierId,
                        'created_at' => $service->created_at,
                        'updated_at' => $service->updated_at
                    ],
                    'relationships' => [
                        'serviceitems' => [
                            'data' => [
                                [
                                    'id'            => $serviceitem1->id,
                                    'type'          => 'serviceitems',
                                    'name'          => $serviceitem1->name,
                                    'category'      => $serviceitem1->category,
                                    'description'   => $serviceitem1->description,
                                    'value'         => $serviceitem1->value,
                                    'unit'          => $serviceitem1->unit,
                                    'cost'          => $serviceitem1->cost,
                                    'domain'        => $serviceitem1->domain,
                                ],
                                [
                                    'id'            => $serviceitem2->id,
                                    'type'          => 'serviceitems',
                                    'name'          => $serviceitem2->name,
                                    'category'      => $serviceitem2->category,
                                    'description'   => $serviceitem2->description,
                                    'value'         => $serviceitem2->value,
                                    'unit'          => $serviceitem2->unit,
                                    'cost'          => $serviceitem2->cost,
                                    'domain'        => $serviceitem2->domain,
                                ]
                            ],
                        ],
                    ]
                ]
            ])
            //Log::debug("testUpdateServiceIncludeAllDeleteRelationships: ".print_r($res->response->getContent(), true));
            ->seeJson(
                [
                    'title' => $service->title,
                    'planCode' => $service->planCode,
                    'cost' => $service->cost,
                    'description' => $service->description,
                    'currency' => $service->currency,
                    'carrierId' => $service->carrierId,
                    'status' => $service->status
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'title',
                            'planCode',
                            'cost',
                            'description',
                            'currency',
                            'carrierId',
                            'status'
                        ],
                        'links' => [
                            'self'
                        ],
                        'relationships' => [
                            'carriers' => [
                                'links' => [
                                    'self',
                                    'related'
                                ],
                                'data' => [
                                    0 => [
                                        'type',
                                        'id'
                                    ]
                                ]
                            ],
                            'serviceitems' => [
                                'links' => [
                                    'self',
                                    'related'
                                ],
                                'data' => [
                                    0 => [
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
                                'name',
                                'presentation',
                                'active',
                                'locationId',
                                'shortName'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        1 => [
                            'type',
                            'id',
                            'attributes' => [
                                'serviceId',
                                'category',
                                'description',
                                'value',
                                'unit',
                                'cost',
                                'domain'
                            ],
                            'links' => [
                                'self'
                            ]
                        ]
                    ]
                ]);
    }

    public function testUpdateServiceIncludeAllAddRelationships()
    {
        $carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();

        $service = factory(\WA\DataStore\Service\Service::class)->create(['carrierId' => $carrier->id]);
        $service2 = factory(\WA\DataStore\Service\Service::class)->create(['carrierId' => $carrier->id]);
 
        // SERVICEITEMS
        $serviceitem1 = factory(\WA\DataStore\ServiceItem\ServiceItem::class)->create(['serviceId' => $service->id]);
        $serviceitem2 = factory(\WA\DataStore\ServiceItem\ServiceItem::class)->create(['serviceId' => $service->id]);
        $serviceitem3 = factory(\WA\DataStore\ServiceItem\ServiceItem::class)->create(['serviceId' => $service2->id]);

        $serviceSItDB = DB::table('service_items')->where('serviceId', $service->id)->get();

        $this->assertCount(2, $serviceSItDB);
        $this->assertEquals($serviceSItDB[0]->serviceId, $service->id);
        $this->assertEquals($serviceSItDB[1]->serviceId, $service->id);

        $serviceitem1DB = DB::table('service_items')->where('serviceId', $service->id)->get()[0];
        $serviceitem2DB = DB::table('service_items')->where('serviceId', $service->id)->get()[1];
        $serviceitem3DB = DB::table('service_items')->where('serviceId', $service2->id)->get()[0];

        $this->assertEquals($serviceitem1DB->id, $serviceitem1->id);
        $this->assertEquals($serviceitem1DB->serviceId, $service->id);
        $this->assertEquals($serviceitem1DB->category, $serviceitem1->category);
        $this->assertEquals($serviceitem1DB->value, $serviceitem1->value);
        $this->assertEquals($serviceitem1DB->description, $serviceitem1->description);
        $this->assertEquals($serviceitem1DB->cost, $serviceitem1->cost);
        $this->assertEquals($serviceitem1DB->unit, $serviceitem1->unit);
        $this->assertEquals($serviceitem1DB->domain, $serviceitem1->domain);

        $this->assertEquals($serviceitem2DB->id, $serviceitem2->id);
        $this->assertEquals($serviceitem2DB->serviceId, $service->id);
        $this->assertEquals($serviceitem2DB->category, $serviceitem2->category);
        $this->assertEquals($serviceitem2DB->value, $serviceitem2->value);
        $this->assertEquals($serviceitem2DB->description, $serviceitem2->description);
        $this->assertEquals($serviceitem2DB->cost, $serviceitem2->cost);
        $this->assertEquals($serviceitem2DB->unit, $serviceitem2->unit);
        $this->assertEquals($serviceitem2DB->domain, $serviceitem2->domain);

        $this->assertEquals($serviceitem3DB->serviceId, $service2->id);
        $this->assertNotEquals($serviceitem3DB->serviceId, $service->id);

        $res = $this->json('PATCH', '/services/'.$service->id.'?include=serviceitems',
            [
                'data' => [
                    'type' => 'services',
                    'attributes' => [
                        'title' => $service->title,
                        'planCode' => $service->planCode,
                        'cost' => $service->cost,
                        'description' => $service->description,
                        'currency' => $service->currency,
                        'carrierId' => $service->carrierId,
                        'status' => $service->status
                    ],
                    'relationships' => [
                        'serviceitems' => [
                            'data' => [
                                ['type' => 'serviceitems', 'id' => $serviceitem1->id],
                                ['type' => 'serviceitems', 'id' => $serviceitem2->id],
                                ['type' => 'serviceitems', 'id' => $serviceitem3->id]
                            ],
                        ]
                    ],
                ],
            ]
            )
            //Log::debug("testUpdateServiceIncludeAllDeleteRelationships: ".print_r($res->response->getContent(), true));
            ->seeJson(
                [
                    'title' => $service->title,
                    'planCode' => $service->planCode,
                    'cost' => $service->cost,
                    'description' => $service->description,
                    'currency' => $service->currency,
                    'carrierId' => $service->carrierId,
                    'status' => $service->status
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'title',
                            'planCode',
                            'cost',
                            'description',
                            'currency',
                            'carrierId',
                            'status'
                        ],
                        'links' => [
                            'self'
                        ],
                        'relationships' => [
                            'carriers' => [
                                'links' => [
                                    'self',
                                    'related'
                                ],
                                'data' => [
                                    0 => [
                                        'type',
                                        'id'
                                    ]
                                ]
                            ],
                            'serviceitems' => [
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
                            ]
                        ]
                    ],
                    'included' => [
                        0 => [
                            'type',
                            'id',
                            'attributes' => [
                                'name',
                                'presentation',
                                'active',
                                'locationId',
                                'shortName'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        1 => [
                            'type',
                            'id',
                            'attributes' => [
                                'serviceId',
                                'category',
                                'description',
                                'value',
                                'unit',
                                'cost',
                                'domain'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        2 => [
                            'type',
                            'id',
                            'attributes' => [
                                'serviceId',
                                'category',
                                'description',
                                'value',
                                'unit',
                                'cost',
                                'domain'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        3 => [
                            'type',
                            'id',
                            'attributes' => [
                                'serviceId',
                                'category',
                                'description',
                                'value',
                                'unit',
                                'cost',
                                'domain'
                            ],
                            'links' => [
                                'self'
                            ]
                        ]
                    ]
                ]);
    }

    public function testDeleteServiceIfExists()
    {
        // CREATE & DELETE
        $service = factory(\WA\DataStore\Service\Service::class)->create();
        $responseDel = $this->call('DELETE', '/services/'.$service->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', '/services/'.$service->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeleteServiceIfNoExists()
    {
        // DELETE NO EXISTING.
        $responseDel = $this->call('DELETE', '/services/1');
        $this->assertEquals(404, $responseDel->status());
    }
}
