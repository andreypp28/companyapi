<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use WA\DataStore\Condition\Condition;

class PackageApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetPackages()
    {
        factory(\WA\DataStore\Package\Package::class, 40)->create();

        $res = $this->json('GET', 'packages');

        $res->seeJsonStructure([
            'data' => [
                0 => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
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
                'next',
                'last',
            ],
        ]);
    }

    public function testGetPackageByIdIfExists()
    {
        $package = factory(\WA\DataStore\Package\Package::class)->create();

        $res = $this->json('GET', 'packages/'.$package->id)
            ->seeJson([
                'type' => 'packages',
                'name' => $package->name,
            ]);

        $res->seeJsonStructure([
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
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

    public function testGetPackageByIdIfNoExists()
    {
        $packageId = factory(\WA\DataStore\Package\Package::class)->create()->id;
        $packageId = $packageId + 10;

        $response = $this->call('GET', '/packages/'.$packageId);
        $this->assertEquals(404, $response->status());
    }

    public function testGetDeviceByIdandIncludesConditions()
    {
        $package = factory(\WA\DataStore\Package\Package::class)->create();

        $condition1 = factory(\WA\DataStore\Condition\Condition::class)->create(['packageId' => $package->id])->id;
        $condition2 = factory(\WA\DataStore\Condition\Condition::class)->create(['packageId' => $package->id])->id;

        $this->json('GET', 'packages/'.$package->id.'?include=conditions')
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
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
                    'relationships' => [
                        'conditions' => [
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
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'packageId',
                            'nameCond',
                            'condition',
                            'value',
                        ],
                        'links' => [
                            'self',
                        ],
                    ],

                ],
            ]);
    }

    public function testGetPackageByIdandIncludesServices()
    {
        //$this->markTestSkipped('Test for older version');

        $package = factory(\WA\DataStore\Package\Package::class)->create();

        $service1 = factory(\WA\DataStore\Service\Service::class)->create()->id;
        $service2 = factory(\WA\DataStore\Service\Service::class)->create()->id;

        $dataServices = array($service1, $service2);

        $package->services()->sync($dataServices);

        $res = $this->json('GET', 'packages/'.$package->id.'?include=services')
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
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
                    'relationships' => [
                        'services' => [
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
                        ],
                        'links' => [
                            'self',
                        ],
                    ],

                ],
            ]);
    }

    public function testGetPackageByIdandIncludesDevices()
    {
        $package = factory(\WA\DataStore\Package\Package::class)->create();
        $devvar = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;
        $package->devicevariations()->sync([$devvar]);

        $res = $this->json('GET', 'packages/'.$package->id.'?include=devicevariations')
        //Log::debug("testGetPackageByIdandIncludesDevices: ".print_r($res->response->getContent(), true));
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
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
                    'relationships' => [
                        'devicevariations' => [
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
                        ],
                        'links' => [
                            'self',
                        ]
                    ]
                ]
            ]);
    }

    public function testGetPackageByIdandIncludesApps()
    {
        $package = factory(\WA\DataStore\Package\Package::class)->create();

        $app1 = factory(\WA\DataStore\App\App::class)->create()->id;
        $app2 = factory(\WA\DataStore\App\App::class)->create()->id;

        $dataApps = array($app1, $app2);

        $package->apps()->sync($dataApps);

        $this->json('GET', 'packages/'.$package->id.'?include=apps')
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
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
                    'relationships' => [
                        'apps' => [
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
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'type',
                            'image',
                            'description',
                        ],
                        'links' => [
                            'self',
                        ],
                    ],

                ],
            ]);
    }

    public function testGetPackageByIdandIncludesAddess()
    {
        $package = factory(\WA\DataStore\Package\Package::class)->create();

        $address1 = factory(\WA\DataStore\Address\Address::class)->create()->id;
        $address2 = factory(\WA\DataStore\Address\Address::class)->create()->id;

        $package->addresses()->sync([$address1, $address2]);

        $this->json('GET', 'packages/'.$package->id.'?include=addresses')
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
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
                    'relationships' => [
                        'addresses' => [
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
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'attn',
                            'phone',
                            'address',
                            'city',
                            'state',
                            'country',
                            'postalCode'
                        ],
                        'links' => [
                            'self',
                        ],
                    ],

                ],
            ]);
    }

    public function testCreatePackage()
    {
        $package = factory(\WA\DataStore\Package\Package::class)->create();

        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $service1 = factory(\WA\DataStore\Service\Service::class)->create()->id;
        $service2 = factory(\WA\DataStore\Service\Service::class)->create()->id;

        $device1 = factory(\WA\DataStore\Device\Device::class)->create()->id;
        $device2 = factory(\WA\DataStore\Device\Device::class)->create()->id;
        $device3 = factory(\WA\DataStore\Device\Device::class)->create()->id;

        $app1 = factory(\WA\DataStore\App\App::class)->create()->id;
        $app2 = factory(\WA\DataStore\App\App::class)->create()->id;

        $address1 = factory(\WA\DataStore\Address\Address::class)->create()->id;
        $address2 = factory(\WA\DataStore\Address\Address::class)->create()->id;


        $this->json('POST', 'packages',
            [
                "data" => [
                    "type" => "packages",
                    "attributes" => [
                        "name" => "namePackage",
                        "companyId" => $companyId
                    ],
                    'relationships' => [
                        'conditions' => [
                            'data' => [
                                [
                                    'id' => 0,
                                    'type' => 'conditions',
                                    'nameCond' => 'Country',
                                    'condition' => 'equal',
                                    'value' => 'Catalonia',
                                    'inputType' => 'string'
                                ],
                                [
                                    'id' => 0,
                                    'type' => 'conditions',
                                    'nameCond' => 'Level',
                                    'condition' => 'greater than',
                                    'value' => '3',
                                    'inputType' => 'number'
                                ],
                                [
                                    'id' => 0,
                                    'type' => 'conditions',
                                    'nameCond' => 'Supervisor?',
                                    'condition' => 'equal',
                                    'value' => 'Yes',
                                    'inputType' => 'boolean'
                                ],
                                
                            ],
                        ],
                        'services' => [
                            'data' => [
                                ['type' => 'services', 'id' => $service1],
                                ['type' => 'services', 'id' => $service2],
                            ],
                        ],
                        'devices' => [
                            'data' => [
                                ['type' => 'devices', 'id' => $device1],
                                ['type' => 'devices', 'id' => $device2],
                                ['type' => 'devices', 'id' => $device3],
                            ],
                        ],
                        'apps' => [
                            'data' => [
                                ['type' => 'apps', 'id' => $app1],
                                ['type' => 'apps', 'id' => $app2],
                            ],
                        ],
                        'addresses' => [
                            'data' => [
                                ['type' => 'addresses', 'id' => $address1],
                                ['type' => 'addresses', 'id' => $address2],
                            ],
                        ]
                    ],
                ],
            ]
            )->seeJson(
            [
                'type' => 'packages',
                'name' => 'namePackage',
            ]);
    }

    public function testCreatePackageReturnNoValidData()
    {
        // 'data' no valid.
        $package = $this->json('POST', 'packages',
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

    public function testCreatePackageReturnNoValidType()
    {
        // 'type' no valid.
        $package = $this->json('POST', 'packages',
            [
                'data' => [
                    'NoValid' => 'packages',
                    'attributes' => [
                        'name' => 'namePackage',
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

    public function testCreatePackageReturnNoValidAttributes()
    {
        // 'attributes' no valid.
        $package = $this->json('POST', 'packages',
            [
                'data' => [
                    'type' => 'packages',
                    'NoValid' => [
                        'name' => 'namePackage',
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

    public function testCreatePackageReturnRelationshipNoExists()
    {
        $address = factory(WA\DataStore\Address\Address::class)->create()->id;
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $package = $this->json('POST', 'packages',
        [
            'data' => [
                'type' => 'packages',
                'attributes' => [
                    'name' => 'namePackage',
                    'companyId' => $companyId
                ],
                'relationships' => [
                    'IgnoreType' => [
                        'data' => [
                            ['type' => 'apps', 'id' => '1'],
                            ['type' => 'apps', 'id' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'packages',
            'name' => 'namePackage',
        ]);
    }

    public function testCreatePackageReturnRelationshipNoExistsData()
    {
        $address = factory(WA\DataStore\Address\Address::class)->create()->id;
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $package = $this->json('POST', 'packages',
        [
            'data' => [
                'type' => 'packages',
                'attributes' => [
                    'name' => 'namePackage',    
                    'companyId' => $companyId
                ],
                'relationships' => [
                    'apps' => [
                        'IgnoreData' => [
                            ['type' => 'apps', 'id' => '1'],
                            ['type' => 'apps', 'id' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'packages',
            'name' => 'namePackage',
        ]);
    }

    public function testCreatePackageReturnRelationshipNoAppsType()
    {
        $address = factory(WA\DataStore\Address\Address::class)->create()->id;
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $package = $this->json('POST', 'packages',
        [
            'data' => [
                'type' => 'packages',
                'attributes' => [
                    'name' => 'namePackage',    
                    'companyId' => $companyId
                ],
                'relationships' => [
                    'apps' => [
                        'data' => [
                            ['type' => 'NoApps', 'id' => '1'],
                            ['type' => 'NoApps', 'id' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'packages',
            'name' => 'namePackage',
        ]);
    }

    public function testCreatePackageReturnRelationshipNoIdExists()
    {
        $address = factory(WA\DataStore\Address\Address::class)->create()->id;
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $package = $this->json('POST', 'packages',
        [
            'data' => [
                'type' => 'packages',
                'attributes' => [
                    'name' => 'namePackage',    
                    'companyId' => $companyId
                ],
                'relationships' => [
                    'apps' => [
                        'data' => [
                            ['type' => 'apps', 'aa' => '1'],
                            ['type' => 'apps', 'aa' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'packages',
            'name' => 'namePackage',
        ]);
    }

    public function testUpdatePackage()
    {
        $package = factory(\WA\DataStore\Package\Package::class)->create(
            ['name' => 'namePackage1']
        );
        $packageAux = factory(\WA\DataStore\Package\Package::class)->create(
            ['name' => 'namePackage2']
        );

        $this->assertNotEquals($package->id, $packageAux->id);
        $this->assertNotEquals($package->name, $packageAux->name);

        $this->PATCH('/packages/'.$package->id,
            [
                'data' => [
                    'type' => 'packages',
                    'attributes' => [
                        'name' => $packageAux->name,
                    ],
                ],
            ])
            ->seeJson(
            [
                'type' => 'packages',
                'name' => $packageAux->name,
            ]);
    }

    public function testDeletePackageIfExists()
    {
        // CREATE & DELETE
        $package = factory(\WA\DataStore\Package\Package::class)->create();
        $responseDel = $this->call('DELETE', '/packages/'.$package->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', '/packages/'.$package->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeletePackageIfNoExists()
    {
        // DELETE NO EXISTING.
        $responseDel = $this->call('DELETE', '/packages/1');
        $this->assertEquals(404, $responseDel->status());
    }

    public function testUserPackagesCheckAttributes() 
    {
        $company = factory(\WA\DataStore\Company\Company::class)->create();
        $user1 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id, 'isSupervisor' => 1]);
        $user2 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id, 'isSupervisor' => 1]);
        $user3 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id, 'isSupervisor' => 0]);

        $res1 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Supervisor?', 'condition' => 'equal', 'value' => 'No']
                    ]
                ]
            ])->seeJson([
                    'number' => 1
            ]);
        
        $res2 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Supervisor?', 'condition' => 'equal', 'value' => 'Yes']
                    ]
                ]
            ])->seeJson([
                    'number' => 2
            ]);

        $res3 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => []
                ]
            ])->seeJson([
                    'number' => 3
            ]);
    }

    public function testUserPackagesCheckAddressEqual() 
    {
        $company = factory(\WA\DataStore\Company\Company::class)->create();
        $user1 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user2 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user3 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user4 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);

        $address1 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'city', 'state' => 'state', 'country' => 'country']);
        $address2 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'city', 'state' => 'state', 'country' => 'country']);
        $address3 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'city', 'state' => 'state', 'country' => 'country']);

        $address5 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Madrid', 'state' => 'Madrid', 'country' => 'Spain']);
        $address6 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Girona', 'state' => 'Girona', 'country' => 'Catalonia']);
        $address7 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Rubi', 'state' => 'Barcelona', 'country' => 'Catalonia']);
        $address8 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Barcelona', 'state' => 'Barcelona', 'country' => 'Catalonia']);

        $user1Address5 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user1->id, 'addressId' => $address5->id]);
        $user1Address1 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user1->id, 'addressId' => $address1->id]);
        $user1Address2 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user1->id, 'addressId' => $address2->id]);
        $user1Address3 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user1->id, 'addressId' => $address3->id]);

        $user2Address1 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user2->id, 'addressId' => $address1->id]);
        $user2Address6 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user2->id, 'addressId' => $address6->id]);
        $user2Address2 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user2->id, 'addressId' => $address2->id]);
        $user2Address3 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user2->id, 'addressId' => $address3->id]);
        
        $user3Address1 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user3->id, 'addressId' => $address1->id]);
        $user3Address2 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user3->id, 'addressId' => $address2->id]);
        $user3Address7 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user3->id, 'addressId' => $address7->id]);
        $user3Address3 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user3->id, 'addressId' => $address3->id]);

        $user4Address1 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user4->id, 'addressId' => $address1->id]);
        $user4Address2 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user4->id, 'addressId' => $address2->id]);
        $user4Address3 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user4->id, 'addressId' => $address3->id]);
        $user4Address8 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user4->id, 'addressId' => $address8->id]);

        $res1 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'equal', 'value' => 'Catalonia'],
                        ['nameCond' => 'State', 'condition' => 'equal', 'value' => 'Barcelona'],
                        ['nameCond' => 'City', 'condition' => 'equal', 'value' => 'Barcelona']
                    ]
                ]
            ])->seeJson([
                    'number' => 1
            ]);

        $res2 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'equal', 'value' => 'Catalonia'],
                        ['nameCond' => 'State', 'condition' => 'equal', 'value' => 'Barcelona'],
                    ]
                ]
            ])->seeJson([
                    'number' => 2
            ]);

        $res3 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'equal', 'value' => 'Catalonia'],
                    ]
                ]
            ])->seeJson([
                    'number' => 3
            ]);

        $res4 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => []
                ]
            ])->seeJson([
                    'number' => 4
            ]);

    }
    public function testUserPackagesCheckAddressNotEqual() 
    {
        $company = factory(\WA\DataStore\Company\Company::class)->create();
        $user1 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user2 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user3 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user4 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);

        $address1 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Madrid', 'state' => 'Madrid', 'country' => 'Spain']);
        $address2 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Girona', 'state' => 'Girona', 'country' => 'Catalonia']);
        $address3 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Rubi', 'state' => 'Barcelona', 'country' => 'Catalonia']);
        $address4 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Barcelona', 'state' => 'Barcelona', 'country' => 'Catalonia']);

        $userAddress1 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user1->id, 'addressId' => $address1->id]);
        $userAddress2 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user2->id, 'addressId' => $address2->id]);
        $userAddress3 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user3->id, 'addressId' => $address3->id]);
        $userAddress4 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user4->id, 'addressId' => $address4->id]);

        $res1 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'equal', 'value' => 'Catalonia'],
                        ['nameCond' => 'State', 'condition' => 'equal', 'value' => 'Barcelona'],
                        ['nameCond' => 'City', 'condition' => 'not equal', 'value' => 'Barcelona']
                    ]
                ]
            ])->seeJson([
                    'number' => 1
            ]);

        $res2 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'equal', 'value' => 'Catalonia'],
                        ['nameCond' => 'State', 'condition' => 'not equal', 'value' => 'Barcelona'],
                    ]
                ]
            ])->seeJson([
                    'number' => 1
            ]);

        $res3 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'not equal', 'value' => 'Catalonia'],
                    ]
                ]
            ])->seeJson([
                    'number' => 1
            ]);

        $res4 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => []
                ]
            ])->seeJson([
                    'number' => 4
            ]);

    }

    public function testUserPackagesCheckAddressContains() 
    {
        $company = factory(\WA\DataStore\Company\Company::class)->create();
        $user1 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user2 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user3 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user4 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);

        $address1 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Madrid', 'state' => 'Madrid', 'country' => 'Spain']);
        $address2 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Girona', 'state' => 'Girona', 'country' => 'Catalonia']);
        $address3 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Rubi', 'state' => 'Barcelona', 'country' => 'Catalonia']);
        $address4 = factory(\WA\DataStore\Address\Address::class)->create(['city' => 'Barcelona', 'state' => 'Barcelona', 'country' => 'Catalonia']);

        $userAddress1 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user1->id, 'addressId' => $address1->id]);
        $userAddress2 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user2->id, 'addressId' => $address2->id]);
        $userAddress3 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user3->id, 'addressId' => $address3->id]);
        $userAddress4 = factory(\WA\DataStore\User\UserAddress::class)->create(['userId' => $user4->id, 'addressId' => $address4->id]);

        $res1 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'equal', 'value' => 'Catalonia'],
                        ['nameCond' => 'State', 'condition' => 'equal', 'value' => 'Barcelona'],
                        ['nameCond' => 'City', 'condition' => 'contains', 'value' => 'Barcelona']
                    ]
                ]
            ])->seeJson([
                    'number' => 1
            ]);

        $res2 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'equal', 'value' => 'Catalonia'],
                        ['nameCond' => 'State', 'condition' => 'contains', 'value' => 'Barcelona'],
                    ]
                ]
            ])->seeJson([
                    'number' => 2
            ]);

        $res3 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'contains', 'value' => 'Catalonia'],
                    ]
                ]
            ])->seeJson([
                    'number' => 3
            ]);

        $res4 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => []
                ]
            ])->seeJson([
                    'number' => 4
            ]);

        $res5 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'equal', 'value' => 'Catalonia'],
                        ['nameCond' => 'State', 'condition' => 'equal', 'value' => 'Barcelona'],
                        ['nameCond' => 'City', 'condition' => 'contains', 'value' => 'ona']
                    ]
                ]
            ])->seeJson([
                    'number' => 1
            ]);

        $res6 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'equal', 'value' => 'Catalonia'],
                        ['nameCond' => 'City', 'condition' => 'contains', 'value' => 'ona'],
                    ]
                ]
            ])->seeJson([
                    'number' => 2
            ]);

        $res7 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'City', 'condition' => 'contains', 'value' => 'ona'],
                    ]
                ]
            ])->seeJson([
                    'number' => 2
            ]);

        $res8 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'contains', 'value' => 'cat']
                    ]
                ]
            ])->seeJson([
                    'number' => 3
            ]);

        $res9 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'contains', 'value' => 'cat'],
                        ['nameCond' => 'Country', 'condition' => 'contains', 'value' => 'alo'],
                        ['nameCond' => 'Country', 'condition' => 'contains', 'value' => 'nia']
                    ]
                ]
            ])->seeJson([
                    'number' => 3
            ]);

        $res9 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Country', 'condition' => 'contains', 'value' => 'cat'],
                        ['nameCond' => 'Country', 'condition' => 'contains', 'value' => 'alo'],
                        ['nameCond' => 'Country', 'condition' => 'contains', 'value' => 'nian']
                    ]
                ]
            ])->seeJson([
                    'number' => 0
            ]);

    }

    public function testUserPackagesCheckUdls() 
    {
        
        $company = factory(\WA\DataStore\Company\Company::class)->create();

        $udl1 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $company->id, 'name' => 'Position']);
        $udl2 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $company->id, 'name' => 'Car']);
        $udl3 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $company->id, 'name' => 'Level']);

        $udl1Value1 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['name' => 'Engineer', 'udlId' => $udl1->id]);
        $udl1Value2 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['name' => 'Administrative', 'udlId' => $udl1->id]);
        $udl1Value3 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['name' => 'Boss', 'udlId' => $udl1->id]);

        $udl2Value1 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['name' => 'BMW', 'udlId' => $udl2->id]);
        $udl2Value2 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['name' => 'Audi', 'udlId' => $udl2->id]);
        $udl2Value3 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['name' => 'Ferrari', 'udlId' => $udl2->id]);
        
        $udl3Value1 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['name' => '1', 'udlId' => $udl3->id]);
        $udl3Value2 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['name' => '3', 'udlId' => $udl3->id]);
        $udl3Value3 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['name' => '5', 'udlId' => $udl3->id]);

        $user1 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user2 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user3 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);

        $user1UV1 = factory(\WA\DataStore\User\UserUdlValue::class)->create(['userId' => $user1->id, 'udlValueId' => $udl1Value1->id]);
        $user1UV2 = factory(\WA\DataStore\User\UserUdlValue::class)->create(['userId' => $user1->id, 'udlValueId' => $udl2Value2->id]);
        $user1UV3 = factory(\WA\DataStore\User\UserUdlValue::class)->create(['userId' => $user1->id, 'udlValueId' => $udl3Value3->id]);

        $user2UV1 = factory(\WA\DataStore\User\UserUdlValue::class)->create(['userId' => $user2->id, 'udlValueId' => $udl1Value1->id]);
        $user2UV2 = factory(\WA\DataStore\User\UserUdlValue::class)->create(['userId' => $user2->id, 'udlValueId' => $udl2Value2->id]);
        $user2UV3 = factory(\WA\DataStore\User\UserUdlValue::class)->create(['userId' => $user2->id, 'udlValueId' => $udl3Value2->id]);

        $user3UV1 = factory(\WA\DataStore\User\UserUdlValue::class)->create(['userId' => $user3->id, 'udlValueId' => $udl1Value2->id]);
        $user3UV2 = factory(\WA\DataStore\User\UserUdlValue::class)->create(['userId' => $user3->id, 'udlValueId' => $udl2Value3->id]);
        $user3UV3 = factory(\WA\DataStore\User\UserUdlValue::class)->create(['userId' => $user3->id, 'udlValueId' => $udl3Value1->id]);

        $res1 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Position', 'condition' => 'equal', 'value' => 'Engineer']
                    ]
                ]
            ])->seeJson([
                    'number' => 2
            ]);

        $res2 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Position', 'condition' => 'equal', 'value' => 'Administrative']
                    ]
                ]
            ])->seeJson([
                    'number' => 1
            ]);

        $res3 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Position', 'condition' => 'equal', 'value' => 'Boss']
                    ]
                ]
            ])->seeJson([
                    'number' => 0
            ]);

        $res4 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Position', 'condition' => 'equal', 'value' => 'Engineer'],
                        ['nameCond' => 'Car', 'condition' => 'contains', 'value' => 'audi']
                    ]
                ]
            ])->seeJson([
                    'number' => 2
            ]);

        $res5 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Position', 'condition' => 'equal', 'value' => 'Engineer'],
                        ['nameCond' => 'Car', 'condition' => 'not equal', 'value' => 'audi']
                    ]
                ]
            ])->seeJson([
                    'number' => 0
            ]);

        $res6 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Level', 'condition' => 'greater than', 'value' => '3']
                    ]
                ]
            ])->seeJson([
                    'number' => 1
            ]);

        $res7 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Level', 'condition' => 'greater or equal', 'value' => '3']
                    ]
                ]
            ])->seeJson([
                    'number' => 2
            ]);

        $res8 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Level', 'condition' => 'less than', 'value' => '3']
                    ]
                ]
            ])->seeJson([
                    'number' => 1
            ]);

        $res9 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Level', 'condition' => 'less or equal', 'value' => '3']
                    ]
                ]
            ])->seeJson([
                    'number' => 2
            ]);

        $res10 = $this->json('POST', '/packages/forUser',
            [
                'data' => [
                    'companyId' => $company->id,
                    'conditions' => [
                        ['nameCond' => 'Position', 'condition' => 'contains', 'value' => 'eer'],
                        ['nameCond' => 'Position', 'condition' => 'contains', 'value' => 'eng'],
                        ['nameCond' => 'Position', 'condition' => 'contains', 'value' => 'neeer'],
                    ]
                ]
            ])->seeJson([
                    'number' => 0
            ]);
    }
}
