<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use WA\DataStore\User\User;
use Laravel\Passport\Bridge\Scope;
use WA\DataStore\Scope\Scope as ScopeModel;
use Laravel\Passport\Passport;

class UsersApiTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic functional test for user endpoints.
     */
    
    public function testGetUsers()
    {
        $user = factory(\WA\DataStore\User\User::class, 20)->create();
        $res = $this->json('GET', '/users')
        //Log::debug("Users: ".print_r($res->response->getContent(), true));
            ->seeJsonStructure([
                'data' => [
                    0 => [
                        'type', 
                        'id',
                        'attributes' => [
                            'uuid',
                            'identification',
                            'email',
                            'alternateEmail',
                            'password',
                            'username',
                            'confirmation_code',
                            'remember_token',
                            'confirmed',
                            'firstName',
                            'lastName',
                            'alternateFirstName',
                            'supervisorEmail',
                            'companyUserIdentifier',
                            'isSupervisor',
                            'isValidator',
                            'isActive',
                            'rgt',
                            'lft',
                            'hierarchy',
                            'defaultLang',
                            'notes',
                            'level',
                            'notify',
                            'companyId',
                            'syncId',
                            'supervisorId',
                            'externalId',
                            'approverId',
                            'defaultLocationId',
                        ],
                        'links' => [
                            'self',
                        ]
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

    public function testGetUserByIdIfExists()
    {
        $user = factory(\WA\DataStore\User\User::class)->create();
        $res = $this->get('/users/'.$user->id)
        //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJson([
                    'type' => 'users',
                    'uuid' => $user->uuid,
                    'identification' => $user->identification,
                    'email' => $user->email,
                    'alternateEmail' => $user->alternateEmail,
                    'username' => $user->username,
                    'confirmation_code' => $user->confirmation_code,
                    'remember_token' => $user->remember_token,
                    'confirmed' => "$user->confirmed",
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'alternateFirstName' => $user->alternateFirstName,
                    'supervisorEmail' => $user->supervisorEmail,
                    'companyUserIdentifier' => "$user->companyUserIdentifier",
                    'isSupervisor' => "$user->isSupervisor",
                    'isValidator' => "$user->isValidator",
                    'isActive' => "$user->isActive",
                    'rgt' => $user->rgt,
                    'lft' => $user->lft,
                    'hierarchy' => $user->hierarchy,
                    'defaultLang' => $user->defaultLang,
                    'notes' => $user->notes,
                    'level' => "$user->level",
                    'notify' => "$user->notify",
                    'companyId' => "$user->companyId",
                    'syncId' => $user->syncId,
                    'supervisorId' => "$user->supervisorId",
                    'externalId' => $user->externalId,
                    'approverId' => "$user->approverId",
                    'defaultLocationId' => "$user->defaultLocationId"
                ])
            ->seeJsonStructure([
                'data' => [
                    'type', 
                    'id',
                    'attributes' => [
                        'uuid',
                        'identification',
                        'email',
                        'alternateEmail',
                        'password',
                        'username',
                        'confirmation_code',
                        'remember_token',
                        'confirmed',
                        'firstName',
                        'lastName',
                        'alternateFirstName',
                        'supervisorEmail',
                        'companyUserIdentifier',
                        'isSupervisor',
                        'isValidator',
                        'isActive',
                        'rgt',
                        'lft',
                        'hierarchy',
                        'defaultLang',
                        'notes',
                        'level',
                        'notify',
                        'companyId',
                        'syncId',
                        'supervisorId',
                        'externalId',
                        'approverId',
                        'defaultLocationId'
                    ],
                    'links' => [
                        'self',
                    ]
                ],
                'meta' => [
                    'sort',
                    'filter',
                    'fields'
                ]
            ]);
    }

    public function testGetUserByIdIfNoExists()
    {
        $userId = factory(\WA\DataStore\User\User::class)->create()->id;
        $userId = $userId + 10;
        $response = $this->call('GET', '/users/'.$userId);
        $this->assertEquals(404, $response->status());
    }

    public function testGetLoggedInUser()
    {
        $grantType = 'password';
        $password = 'user';
        $user = factory(\WA\DataStore\User\User::class)->create([
            'email' => 'email@email.com',
            'password' => '$2y$10$oc9QZeaYYAd.8BPGmXGaFu9cAycKTcBu7LRzmT2J231F0BzKwpxj6'
        ]);
        $scope = factory(\WA\DataStore\Scope\Scope::class)->create(['name' => 'get', 'display_name'=>'get']);
        $role = factory(\WA\DataStore\Role\Role::class)->create();
        $permission1 = factory(\WA\DataStore\Permission\Permission::class)->create();
        $permission2 = factory(\WA\DataStore\Permission\Permission::class)->create();
        $user->roles()->sync([$role->id]);
        $role->perms()->sync([$permission1->id,$permission2->id]);
        $scope->permissions()->sync([$permission1->id,$permission2->id]);
        
        $scp = $scope->name;
        $oauth = factory(\WA\DataStore\Oauth\Oauth::class)->create([
            'user_Id' => null,
            'name' => 'Password Grant Client',
            'secret' => 'ab9QdKGBXZmZn50aPlf4bLlJtC4BJJNC0M99i7B7',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
        ]);
        // Setup TokensCan as in AuthSericeProvider, as it is not properly executed on app bootstrap during the test
        $scopes = ScopeModel::all();
            
        $listScope = array();
        foreach ($scopes as $scop){
            $listScope[$scop->getAttributes()['name']] = $scop->getAttributes()['description'];
        }

        Passport::tokensCan($listScope);

        $body = [
            'grant_type' => $grantType,
            'username' => $user->email,
            'password' => $password,
            'client_id' => $oauth->id,
            'client_secret' => $oauth->secret,
            'scope'=> $scp
        ];
        $call = $this->call('POST', 'oauth/token', $body, [], [], [], true );
        $array = (array)json_decode($call->getContent());
        $bearerToken = $array['token_type'].' '.$array['access_token'];
        $this->be($user);
        $res = $this->call('GET', 'users/me', [], [], [], ['Accept' => 'application/vnd.v1+json', 'Authorization' => $bearerToken], true );
        $resArray = (array)json_decode($res->getContent());
        $this->assertEquals($resArray['identification'], $user->identification);
        $this->assertEquals($resArray['email'], $user->email);
        $this->assertEquals($resArray['alternateEmail'], $user->alternateEmail);
        $this->assertEquals($resArray['username'], $user->username);
        $this->assertEquals($resArray['firstName'], $user->firstName);
        $this->assertEquals($resArray['lastName'], $user->lastName);
        $this->assertEquals($resArray['alternateFirstName'], $user->alternateFirstName);
        $this->assertEquals($resArray['supervisorEmail'], $user->supervisorEmail);
        $this->assertEquals($resArray['companyUserIdentifier'], $user->companyUserIdentifier);
        $this->assertEquals($resArray['isSupervisor'], $user->isSupervisor);
        $this->assertEquals($resArray['isValidator'], $user->isValidator);
        $this->assertEquals($resArray['rgt'], $user->rgt);
        $this->assertEquals($resArray['lft'], $user->lft);
        $this->assertEquals($resArray['hierarchy'], $user->hierarchy);
        $this->assertEquals($resArray['defaultLang'], $user->defaultLang);
        $this->assertEquals($resArray['notes'], $user->notes);
        $this->assertEquals($resArray['level'], $user->level);
        $this->assertEquals($resArray['notify'], $user->notify);
        $this->assertEquals($resArray['companyId'], $user->companyId);
        $this->assertEquals($resArray['syncId'], $user->syncId);
        $this->assertEquals($resArray['supervisorId'], $user->supervisorId);
        $this->assertEquals($resArray['approverId'], $user->approverId);
        $this->assertEquals($resArray['defaultLocationId'], $user->defaultLocationId);
    }
    
    public function testGetUserByIdandIncludesAssets()
    {
        $user = factory(\WA\DataStore\User\User::class)->create();
        $asset1 = factory(\WA\DataStore\Asset\Asset::class)->create(['userId' => $user->id])->id;
        $asset2 = factory(\WA\DataStore\Asset\Asset::class)->create(['userId' => $user->id])->id;

        $response = $this->json('GET', 'users/'.$user->id.'?include=assets')
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'uuid',
                        'identification',
                        'email',
                        'alternateEmail',
                        'password',
                        'username',
                        'confirmation_code',
                        'remember_token',
                        'confirmed',
                        'firstName',
                        'lastName',
                        'alternateFirstName',
                        'supervisorEmail',
                        'companyUserIdentifier',
                        'isSupervisor',
                        'isValidator',
                        'isActive',
                        'rgt',
                        'lft',
                        'hierarchy',
                        'defaultLang',
                        'notes',
                        'level',
                        'notify',
                        'companyId',
                        'syncId',
                        'supervisorId',
                        'externalId',
                        'approverId',
                        'defaultLocationId'
                    ],
                    'links' => [
                        'self',
                    ],
                    'relationships' => [
                        'assets' => [
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
                            'identification',
                            'active',
                            'statusId',
                            'typeId',
                            'externalId',
                            'carrierId',
                            'syncId',
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                ],
            ]);
    }

    public function testGetUserByIdandIncludesDeviceVariations()
    {
        $user = factory(\WA\DataStore\User\User::class)->create();

        $deviceVariation1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;
        $deviceVariation2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;
        $datadevicevariations = array($deviceVariation1, $deviceVariation2);
        $user->devicevariations()->sync($datadevicevariations);
        
        $res = $this->json('GET', 'users/'.$user->id.'?include=devicevariations')
        //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'uuid',
                        'identification',
                        'email',
                        'alternateEmail',
                        'password',
                        'username',
                        'confirmation_code',
                        'remember_token',
                        'confirmed',
                        'firstName',
                        'lastName',
                        'alternateFirstName',
                        'supervisorEmail',
                        'companyUserIdentifier',
                        'isSupervisor',
                        'isValidator',
                        'isActive',
                        'rgt',
                        'lft',
                        'hierarchy',
                        'defaultLang',
                        'notes',
                        'level',
                        'notify',
                        'companyId',
                        'syncId',
                        'supervisorId',
                        'externalId',
                        'approverId',
                        'defaultLocationId'
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
                                1 => [
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
                            'companyId',
                        ],
                        'links' => [
                            'self',
                        ],
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
                            'companyId',
                        ],
                        'links' => [
                            'self',
                        ]
                    ]
                ]
            ]);
    }

    public function testGetUserByIdandIncludesRoles()
    {
        $user = factory(\WA\DataStore\User\User::class)->create();
        $role1 = factory(\WA\DataStore\Role\Role::class)->create()->id;
        $role2 = factory(\WA\DataStore\Role\Role::class)->create()->id;
        $dataroles = array($role1, $role2);
        $user->roles()->sync($dataroles);
        $res = $this->json('GET', 'users/'.$user->id.'?include=roles')
        //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'uuid',
                        'identification',
                        'email',
                        'alternateEmail',
                        'password',
                        'username',
                        'confirmation_code',
                        'remember_token',
                        'confirmed',
                        'firstName',
                        'lastName',
                        'alternateFirstName',
                        'supervisorEmail',
                        'companyUserIdentifier',
                        'isSupervisor',
                        'isValidator',
                        'isActive',
                        'rgt',
                        'lft',
                        'hierarchy',
                        'defaultLang',
                        'notes',
                        'level',
                        'notify',
                        'companyId',
                        'syncId',
                        'supervisorId',
                        'externalId',
                        'approverId',
                        'defaultLocationId'
                    ],
                    'links' => [
                        'self',
                    ],
                    'relationships' => [
                        'roles' => [
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
                            ]
                        ]
                    ]
                ],
                'included' => [
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'name'
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                    1 => [
                        'type',
                        'id',
                        'attributes' => [
                            'name'
                        ],
                        'links' => [
                            'self',
                        ]
                    ]
                ]
            ]);
    }
    
    public function testGetUserByIdandIncludesUdls()
    {
        $user = factory(\WA\DataStore\User\User::class)->create();
        $company1 = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $company2 = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $udl1 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $company1])->id;
        $udl2 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $company2])->id;
        $udlV1 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['udlId' => $udl1])->id;
        $udlV2 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['udlId' => $udl2])->id;
        $dataudls = array($udlV1, $udlV2);
        $user->udlValues()->sync($dataudls);
        $res = $this->json('GET', 'users/'.$user->id.'?include=udls')
        //Log::debug("testGetUserByIdandIncludesUdls: ".print_r($res->response->getContent(), true));
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'uuid',
                        'identification',
                        'email',
                        'alternateEmail',
                        'password',
                        'username',
                        'confirmation_code',
                        'remember_token',
                        'confirmed',
                        'firstName',
                        'lastName',
                        'alternateFirstName',
                        'supervisorEmail',
                        'companyUserIdentifier',
                        'isSupervisor',
                        'isValidator',
                        'isActive',
                        'rgt',
                        'lft',
                        'hierarchy',
                        'defaultLang',
                        'notes',
                        'level',
                        'notify',
                        'companyId',
                        'syncId',
                        'supervisorId',
                        'externalId',
                        'approverId',
                        'defaultLocationId'
                    ],
                    'links' => [
                        'self',
                    ],
                    'relationships' => [
                        'udls' => [
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
                            'udlId',
                            'udlValue'
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                ],
            ]);
    }
    
    public function testGetUserByIdandIncludesCompanies()
    {
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $user = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);
        $res = $this->json('GET', 'users/'.$user->id.'?include=companies')
        //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'uuid',
                        'identification',
                        'email',
                        'alternateEmail',
                        'password',
                        'username',
                        'confirmation_code',
                        'remember_token',
                        'confirmed',
                        'firstName',
                        'lastName',
                        'alternateFirstName',
                        'supervisorEmail',
                        'companyUserIdentifier',
                        'isSupervisor',
                        'isValidator',
                        'isActive',
                        'rgt',
                        'lft',
                        'hierarchy',
                        'defaultLang',
                        'notes',
                        'level',
                        'notify',
                        'companyId',
                        'syncId',
                        'supervisorId',
                        'externalId',
                        'approverId',
                        'defaultLocationId'
                    ],
                    'links' => [
                        'self',
                    ],
                    'relationships' => [
                        'companies' => [
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
                            'label',
                            'active',
                            'assetPath',
                            'currentBillMonth',
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                ],
            ]);
    }
    
    public function testGetUserByIdandIncludesAllocations()
    {
        $user = factory(\WA\DataStore\User\User::class)->create();

        $allocation1 = factory(\WA\DataStore\Allocation\Allocation::class)->create(['userId' => $user->id]);
        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        $allocation1->carriers()->associate($carrier1);
        $allocation1->save();

        $allocation2 = factory(\WA\DataStore\Allocation\Allocation::class)->create(['userId' => $user->id]);
        $carrier2 = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        $allocation2->carriers()->associate($carrier2);
        $allocation2->save();

        $res = $this->json('GET', 'users/'.$user->id.'?include=allocations')
        //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'uuid',
                        'identification',
                        'email',
                        'alternateEmail',
                        'password',
                        'username',
                        'confirmation_code',
                        'remember_token',
                        'confirmed',
                        'firstName',
                        'lastName',
                        'alternateFirstName',
                        'supervisorEmail',
                        'companyUserIdentifier',
                        'isSupervisor',
                        'isValidator',
                        'isActive',
                        'rgt',
                        'lft',
                        'hierarchy',
                        'defaultLang',
                        'notes',
                        'level',
                        'notify',
                        'companyId',
                        'syncId',
                        'supervisorId',
                        'externalId',
                        'approverId',
                        'defaultLocationId'
                    ],
                    'links' => [
                        'self',
                    ],
                    'relationships' => [
                        'allocations' => [
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
                            'bill_month',
                            'carrier',
                            'mobile_number',
                            'currency',
                            'device',
                            'allocated_charge',
                            'service_plan_charge',
                            'usage_charge',
                            'other_charge',
                            'fees_charge',
                            'last_upgrade',
                        ],
                        'links' => [
                            'self',
                        ],
                    ]
                ]
            ]);
    }
    
    public function testGetUserByIdandIncludesContents()
    {
        $user = factory(\WA\DataStore\User\User::class)->create();
        $content1 = factory(\WA\DataStore\Content\Content::class)->create(['owner_id' => $user->id])->id;
        $content2 = factory(\WA\DataStore\Content\Content::class)->create(['owner_id' => $user->id])->id;
        $res = $this->json('GET', 'users/'.$user->id.'?include=contents')
        //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'uuid',
                        'identification',
                        'email',
                        'alternateEmail',
                        'password',
                        'username',
                        'confirmation_code',
                        'remember_token',
                        'confirmed',
                        'firstName',
                        'lastName',
                        'alternateFirstName',
                        'supervisorEmail',
                        'companyUserIdentifier',
                        'isSupervisor',
                        'isValidator',
                        'isActive',
                        'rgt',
                        'lft',
                        'hierarchy',
                        'defaultLang',
                        'notes',
                        'level',
                        'notify',
                        'companyId',
                        'syncId',
                        'supervisorId',
                        'externalId',
                        'approverId',
                        'defaultLocationId'
                    ],
                    'links' => [
                        'self',
                    ],
                    'relationships' => [
                        'contents' => [
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
                            'content',
                            'active',
                            'owner_type',
                            'owner_id'
                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                ],
            ]);
    }

    public function testCreateUser()
    {
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $companyDomain = factory(\WA\DataStore\Company\CompanyDomains::class)->create(['domain' => 'email.com', 'companyId' => $companyId]);
        
        $user = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);

        $addressId = factory(\WA\DataStore\Address\Address::class)->create()->id;

        //$asset1 = factory(\WA\DataStore\Asset\Asset::class)->create()->id;
        //$asset2 = factory(\WA\DataStore\Asset\Asset::class)->create()->id;
        
        $devicevariation1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;
        $devicevariation2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;
        
        $role1 = factory(\WA\DataStore\Role\Role::class)->create()->id;
        $role2 = factory(\WA\DataStore\Role\Role::class)->create()->id;
        
        $udl1 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $companyId])->id;
        $udl2 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $companyId])->id;
        
        $udlV1 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['udlId' => $udl1])->id;
        $udlV2 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['udlId' => $udl2])->id;
        
        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create();

        $allocation1 = factory(\WA\DataStore\Allocation\Allocation::class)->create();
        $allocation1->carriers()->associate($carrier1);
        $allocation1->save();

        $allocation2 = factory(\WA\DataStore\Allocation\Allocation::class)->create();
        $allocation2->carriers()->associate($carrier1);
        $allocation2->save();

        $content1 = factory(\WA\DataStore\Content\Content::class)->create();
        $content2 = factory(\WA\DataStore\Content\Content::class)->create();

        $service1 = factory(\WA\DataStore\Service\Service::class)->create();
        $service2 = factory(\WA\DataStore\Service\Service::class)->create();


        // assets include deleted.
        $res = $this->json('POST', '/users?include=devicevariations,roles,udls,allocations,companies,contents,addresses',
            [
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'uuid' => $user->uuid,
                        'email' => 'user@email.com',
                        'alternateEmail' => $user->alternateEmail,
                        'password' => $user->password,
                        'username' => $user->username,
                        'confirmation_code' => $user->confirmation_code,
                        'remember_token' => $user->remember_token,
                        'confirmed' => $user->confirmed,
                        'firstName' => $user->firstName,
                        'lastName' => $user->lastName,
                        'alternateFirstName' => $user->alternateFirstName,
                        'supervisorEmail' => $user->supervisorEmail,
                        'companyUserIdentifier' => $user->companyUserIdentifier,
                        'isSupervisor' => $user->isSupervisor,
                        'isValidator' => $user->isValidator,
                        'isActive' => $user->isActive,
                        'rgt' => $user->rgt,
                        'lft' => $user->lft,
                        'hierarchy' => $user->hierarchy,
                        'defaultLang' => $user->defaultLang,
                        'notes' => $user->notes,
                        'level' => $user->level,
                        'notify' => $user->notify,
                        'companyId' => $user->companyId,
                        'syncId' => $user->syncId,
                        'supervisorId' => $user->supervisorId,
                        'externalId' => $user->externalId,
                        'approverId' => $user->approverId,
                        'defaultLocationId' => $user->defaultLocationId
                    ],
                    'relationships' => [
                        'addresses' => [
                            'data' => [
                                ['type' => 'addresses', 'id' => $addressId]
                            ],
                        ],
/*                        'assets' => [
                            'data' => [
                                ['type' => 'assets', 'id' => $asset1],
                                ['type' => 'assets', 'id' => $asset2],
                            ],
                        ],
*/
                        'devicevariations' => [
                            'data' => [
                                ['type' => 'devicevariations', 'id' => $devicevariation1],
                                ['type' => 'devicevariations', 'id' => $devicevariation2],
                            ],
                        ],
                        'services' => [
                            'data' => [
                                ['type' => 'services', 'id' => $service1],
                                ['type' => 'services', 'id' => $service2],
                            ],
                        ],
                        'roles' => [
                            'data' => [
                                ['type' => 'roles', 'id' => $role1],
                                ['type' => 'roles', 'id' => $role2]
                            ],
                        ],
                        'udls' => [
                            'data' => [
                                ['type' => 'udls', 'id' => $udlV1],
                                ['type' => 'udls', 'id' => $udlV2],
                            ],
                        ],
                        'allocations' => [
                            'data' => [
                                [
                                    'id' => $allocation1->id,
                                    'type' => 'allocations',
                                    'billMonth' => $allocation1->billMonth,
                                    'mobileNumber' => $allocation1->mobileNumber,
                                    'carrier' => $allocation1->carrier,
                                    'currency' => $allocation1->currency,
                                    'handsetModel' => $allocation1->handsetModel,
                                    'totalAllocatedCharge' => $allocation1->totalAllocatedCharge,
                                    'preAllocatedAmountDue' => $allocation1->preAllocatedAmountDue,
                                    'otherAdjustments' => $allocation1->otherAdjustments,
                                    'preAdjustedAccessCharge' => $allocation1->preAdjustedAccessCharge,
                                    'adjustedAccessCharge' => $allocation1->adjustedAccessCharge,
                                    'bBCharge' => $allocation1->bBCharge,
                                    'pDACharge' => $allocation1->pDACharge,
                                    'iPhoneCharge' => $allocation1->iPhoneCharge,
                                    'featuresCharge' => $allocation1->featuresCharge,
                                    'dataCardCharge' => $allocation1->dataCardCharge,
                                    'lDCanadaCharge' => $allocation1->lDCanadaCharge,
                                    'uSAddOnPlanCharge' => $allocation1->uSAddOnPlanCharge,
                                    'uSLDAddOnPlanCharge' => $allocation1->uSAddOnPlanCharge,
                                    'uSDataRoamingCharge' => $allocation1->uSDataRoamingCharge,
                                    'nightAndWeekendAddOnCharge' => $allocation1->nightAndWeekendAddOnCharge,
                                    'minuteAddOnCharge' => $allocation1->minuteAddOnCharge,
                                    'servicePlanCharge' => $allocation1->servicePlanCharge,
                                    'directConnectCharge' => $allocation1->directConnectCharge,
                                    'textMessagingCharge' => $allocation1->textMessagingCharge,
                                    'dataCharge' => $allocation1->dataCharge,
                                    'intlRoamingCharge' => $allocation1->intlRoamingCharge,
                                    'intlLongDistanceCharge' => $allocation1->intlLongDistanceCharge,
                                    'directoryAssistanceCharge' => $allocation1->directoryAssistanceCharge,
                                    'callForwardingCharge' => $allocation1->callForwardingCharge,
                                    'airtimeCharge' => $allocation1->airtimeCharge,
                                    'usageCharge' => $allocation1->usageCharge,
                                    'equipmentCharge' => $allocation1->equipmentCharge,
                                    'otherDiscountCharge' => $allocation1->otherDiscountCharge,
                                    'taxesCharge' => $allocation1->taxesCharge,
                                    'thirdPartyCharge' => $allocation1->thirdPartyCharge,
                                    'otherCharge' => $allocation1->otherCharge,
                                    'waFees' => $allocation1->waFees,
                                    'lineFees' => $allocation1->lineFees,
                                    'mobilityFees' => $allocation1->mobilityFees,
                                    'feesCharge' => $allocation1->feesCharge,
                                    'last_upgrade' => $allocation1->last_upgrade,
                                    'deviceType' => $allocation1->deviceType,
                                    'domesticUsageCharge' => $allocation1->domesticUsageCharge,
                                    'domesticDataUsage' => $allocation1->domesticDataUsage,
                                    'domesticVoiceUsage' => $allocation1->domesticVoiceUsage,
                                    'domesticTextUsage' => $allocation1->domesticTextUsage,
                                    'intlRoamUsageCharge' => $allocation1->intlRoamUsageCharge,
                                    'intlRoamDataUsage' => $allocation1->intlRoamDataUsage,
                                    'intlRoamVoiceUsage' => $allocation1->intlRoamVoiceUsage,
                                    'intlRoamTextUsage' => $allocation1->intlRoamTextUsage,
                                    'intlLDUsageCharge' => $allocation1->intlLDUsageCharge,
                                    'intlLDVoiceUsage' => $allocation1->intlLDVoiceUsage,
                                    'intlLDTextUsage' => $allocation1->intlLDTextUsage,
                                    'etfCharge' => $allocation1->etfCharge,
                                    'otherCarrierCharge' => $allocation1->otherCarrierCharge,
                                    'deviceEsnImei' => $allocation1->deviceEsnImei,
                                    'deviceSim' => $allocation1->deviceSim
                                ],
                                [
                                    'id' => $allocation2->id,
                                    'type' => 'allocations',
                                    'billMonth' => $allocation2->billMonth,
                                    'mobileNumber' => $allocation2->mobileNumber,
                                    'carrier' => $allocation2->carrier,
                                    'currency' => $allocation2->currency,
                                    'handsetModel' => $allocation2->handsetModel,
                                    'totalAllocatedCharge' => $allocation2->totalAllocatedCharge,
                                    'preAllocatedAmountDue' => $allocation2->preAllocatedAmountDue,
                                    'otherAdjustments' => $allocation2->otherAdjustments,
                                    'preAdjustedAccessCharge' => $allocation2->preAdjustedAccessCharge,
                                    'adjustedAccessCharge' => $allocation2->adjustedAccessCharge,
                                    'bBCharge' => $allocation2->bBCharge,
                                    'pDACharge' => $allocation2->pDACharge,
                                    'iPhoneCharge' => $allocation2->iPhoneCharge,
                                    'featuresCharge' => $allocation2->featuresCharge,
                                    'dataCardCharge' => $allocation2->dataCardCharge,
                                    'lDCanadaCharge' => $allocation2->lDCanadaCharge,
                                    'uSAddOnPlanCharge' => $allocation2->uSAddOnPlanCharge,
                                    'uSLDAddOnPlanCharge' => $allocation2->uSAddOnPlanCharge,
                                    'uSDataRoamingCharge' => $allocation2->uSDataRoamingCharge,
                                    'nightAndWeekendAddOnCharge' => $allocation2->nightAndWeekendAddOnCharge,
                                    'minuteAddOnCharge' => $allocation2->minuteAddOnCharge,
                                    'servicePlanCharge' => $allocation2->servicePlanCharge,
                                    'directConnectCharge' => $allocation2->directConnectCharge,
                                    'textMessagingCharge' => $allocation2->textMessagingCharge,
                                    'dataCharge' => $allocation2->dataCharge,
                                    'intlRoamingCharge' => $allocation2->intlRoamingCharge,
                                    'intlLongDistanceCharge' => $allocation2->intlLongDistanceCharge,
                                    'directoryAssistanceCharge' => $allocation2->directoryAssistanceCharge,
                                    'callForwardingCharge' => $allocation2->callForwardingCharge,
                                    'airtimeCharge' => $allocation2->airtimeCharge,
                                    'usageCharge' => $allocation2->usageCharge,
                                    'equipmentCharge' => $allocation2->equipmentCharge,
                                    'otherDiscountCharge' => $allocation2->otherDiscountCharge,
                                    'taxesCharge' => $allocation2->taxesCharge,
                                    'thirdPartyCharge' => $allocation2->thirdPartyCharge,
                                    'otherCharge' => $allocation2->otherCharge,
                                    'waFees' => $allocation2->waFees,
                                    'lineFees' => $allocation2->lineFees,
                                    'mobilityFees' => $allocation2->mobilityFees,
                                    'feesCharge' => $allocation2->feesCharge,
                                    'last_upgrade' => $allocation2->last_upgrade,
                                    'deviceType' => $allocation2->deviceType,
                                    'domesticUsageCharge' => $allocation2->domesticUsageCharge,
                                    'domesticDataUsage' => $allocation2->domesticDataUsage,
                                    'domesticVoiceUsage' => $allocation2->domesticVoiceUsage,
                                    'domesticTextUsage' => $allocation2->domesticTextUsage,
                                    'intlRoamUsageCharge' => $allocation2->intlRoamUsageCharge,
                                    'intlRoamDataUsage' => $allocation2->intlRoamDataUsage,
                                    'intlRoamVoiceUsage' => $allocation2->intlRoamVoiceUsage,
                                    'intlRoamTextUsage' => $allocation2->intlRoamTextUsage,
                                    'intlLDUsageCharge' => $allocation2->intlLDUsageCharge,
                                    'intlLDVoiceUsage' => $allocation2->intlLDVoiceUsage,
                                    'intlLDTextUsage' => $allocation2->intlLDTextUsage,
                                    'etfCharge' => $allocation2->etfCharge,
                                    'otherCarrierCharge' => $allocation2->otherCarrierCharge,
                                    'deviceEsnImei' => $allocation2->deviceEsnImei,
                                    'deviceSim' => $allocation2->deviceSim
                                ]
                            ]
                        ],
                        'contents' => [
                            'data' => [
                                [
                                    'type' => 'contents',
                                    'content' => $content1->content,
                                    'active' => $content1->active,
                                    'owner_type' => $content1->owner_type
                                ],
                                [
                                    'type' => 'contents',
                                    'content' => $content2->content,
                                    'active' => $content2->active,
                                    'owner_type' => $content2->owner_type
                                ]
                            ]
                        ]
                    ],
                ],
            ]
            );
            Log::debug("testCreateUser: ".print_r($res->response->getContent(), true));
            $res->seeJson(
                [
                    'uuid' => $user->uuid,
                    'alternateEmail' => $user->alternateEmail,
                    'username' => $user->username,
                    'confirmation_code' => $user->confirmation_code,
                    'remember_token' => $user->remember_token,
                    'confirmed' => $user->confirmed,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'alternateFirstName' => $user->alternateFirstName,
                    'supervisorEmail' => $user->supervisorEmail,
                    'companyUserIdentifier' => $user->companyUserIdentifier,
                    'isSupervisor' => $user->isSupervisor,
                    'isValidator' => $user->isValidator,
                    'isActive' => $user->isActive,
                    'rgt' => $user->rgt,
                    'lft' => $user->lft,
                    'hierarchy' => $user->hierarchy,
                    'defaultLang' => $user->defaultLang,
                    'notes' => $user->notes,
                    'level' => $user->level,
                    'notify' => $user->notify,
                    'companyId' => $user->companyId,
                    'syncId' => $user->syncId,
                    'supervisorId' => $user->supervisorId,
                    'externalId' => $user->externalId,
                    'approverId' => $user->approverId,
                    'defaultLocationId' => $user->defaultLocationId
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'uuid',
                            'email',
                            'alternateEmail',
                            'password',
                            'username',
                            'confirmation_code',
                            'remember_token',
                            'confirmed',
                            'firstName',
                            'lastName',
                            'alternateFirstName',
                            'supervisorEmail',
                            'companyUserIdentifier',
                            'isSupervisor',
                            'isValidator',
                            'isActive',
                            'rgt',
                            'lft',
                            'hierarchy',
                            'defaultLang',
                            'notes',
                            'level',
                            'notify',
                            'companyId',
                            'syncId',
                            'supervisorId',
                            'externalId',
                            'approverId',
                            'defaultLocationId'
                        ],
                        'links' => [
                            'self'
                        ],
                        'relationships' => [
                            'addresses' => [
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
/*                            'assets' => [
                                'links' => [
                                    'self',
                                    'related'
                                ],
                                'data' => [
                                ]
                            ],
*/
                            'devicevariations' => [
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
                                    ]
                                ]
                            ],
                            'companies' => [
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
                            'roles' => [
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
                                    ]
                                ]
                            ],
                            'allocations' => [
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
                                    ]
                                ]
                            ],
                            'contents' => [
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
                                    ]
                                ]
                            ],
                            'udls' => [
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
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'included' => [
/*                        0 => [ // ASSETS
                            "type",
                            "id",
                            "attributes" => [
                                "identification",
                                "active",
                                "statusId",
                                "typeId",
                                "externalId",
                                "carrierId",
                                "syncId",
                                "userId"
                            ],
                            "links" => [
                                "self"
                            ]
                        ],
                        1 => [ // ASSETS
                            "type",
                            "id",
                            "attributes" => [
                                "identification",
                                "active",
                                "statusId",
                                "typeId",
                                "externalId",
                                "carrierId",
                                "syncId",
                                "userId"
                            ],
                            "links" => [
                                "self"
                            ]
                        ],
*/
                        0 => [ // COMPANIES
                            'type',
                            'id',
                            'attributes' => [
                                'name',
                                'label',
                                'active',
                                'udlpath',
                                'isCensus',
                                'udlPathRule',
                                'assetPath',
                                'currentBillMonth'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        1 => [ // DEVICEVARIANTS
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
                        2 => [ // DEVICEVARIANTS
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
                        3 => [ // ROLES
                            'type',
                            'id',
                            'attributes' => [
                                'name'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        4 => [ // ROLES
                            'type',
                            'id',
                            'attributes' => [
                                'name'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        5 => [ // ALLOCATIONS
                            'type',
                            'id',
                            'attributes' => [
                                'bill_month',
                                'carrier',
                                'mobile_number',
                                'currency',
                                'device',
                                'allocated_charge',
                                'service_plan_charge',
                                'usage_charge',
                                'other_charge',
                                'fees_charge',
                                'last_upgrade'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        6 => [ // ALLOCATIONS
                            'type',
                            'id',
                            'attributes' => [
                                'bill_month',
                                'carrier',
                                'mobile_number',
                                'currency',
                                'device',
                                'allocated_charge',
                                'service_plan_charge',
                                'usage_charge',
                                'other_charge',
                                'fees_charge',
                                'last_upgrade'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        7 => [  // CONTENTS
                            'type',
                            'id',
                            'attributes' => [
                                'content',
                                'active',
                                'owner_type',
                                'owner_id'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        8 => [ // CONTENTS
                            'type',
                            'id',
                            'attributes' => [
                                'content',
                                'active',
                                'owner_type',
                                'owner_id'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        9 => [ // UDLS
                            'type',
                            'id',
                            'attributes' => [
                                'udlId',
                                'udlValue'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        10 => [ // UDLS
                            'type',
                            'id',
                            'attributes' => [
                                'udlId',
                                'udlValue'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        11 => [ // ADDRESS
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
                                'self'
                            ]
                        ]
                    ]
                ]);
    }

    public function testCreateUserReturnNoValidData()
    {
        // 'data' no valid.
        $user = $this->json('POST', '/users',
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

    public function testCreateUserReturnNoValidType()
    {
        // 'type' no valid.
        $user = $this->json('POST', '/users',
            [
                'data' => [
                    'NoValid' => 'users',
                    'attributes' => [
                        'email' => 'email@prueba.com',
                        'username' => 'username',
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

    public function testCreateUserReturnNoValidAttributes()
    {
        // 'attributes' no valid.
        $user = $this->json('POST', '/users',
            [
                'data' => [
                    'type' => 'users',
                    'NoValid' => [
                        'email' => 'email@prueba.com',
                        'username' => 'username',
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
    
    public function testCreateUserReturnRelationshipNoExists()
    {
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $companyDomain = factory(\WA\DataStore\Company\CompanyDomains::class)->create(['domain' => 'email.com', 'companyId' => $companyId]);
        $user = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);

        $res = $this->json('POST', '/users?include=assets',
            [
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'uuid' => $user->uuid,
                        'email' => 'user@email.com',
                        'alternateEmail' => $user->alternateEmail,
                        'password' => $user->password,
                        'username' => $user->username,
                        'confirmation_code' => $user->confirmation_code,
                        'remember_token' => $user->remember_token,
                        'confirmed' => $user->confirmed,
                        'firstName' => $user->firstName,
                        'lastName' => $user->lastName,
                        'alternateFirstName' => $user->alternateFirstName,
                        'supervisorEmail' => $user->supervisorEmail,
                        'companyUserIdentifier' => $user->companyUserIdentifier,
                        'isSupervisor' => $user->isSupervisor,
                        'isValidator' => $user->isValidator,
                        'isActive' => $user->isActive,
                        'rgt' => $user->rgt,
                        'lft' => $user->lft,
                        'hierarchy' => $user->hierarchy,
                        'defaultLang' => $user->defaultLang,
                        'notes' => $user->notes,
                        'level' => $user->level,
                        'notify' => $user->notify,
                        'companyId' => $user->companyId,
                        'syncId' => $user->syncId,
                        'supervisorId' => $user->supervisorId,
                        'externalId' => $user->externalId,
                        'approverId' => $user->approverId,
                        'defaultLocationId' => $user->defaultLocationId
                    ],
                    'NoRelationships' => [
                        'assets' => [
                            'data' => [
                                ['type' => 'assets', 'id' => 1],
                                ['type' => 'assets', 'id' => 2],
                            ],
                        ]
                    ],
                ],
            ]
            )
            //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJson(
                [
                    'uuid' => $user->uuid,
                    'alternateEmail' => $user->alternateEmail,
                    'username' => $user->username,
                    'confirmation_code' => $user->confirmation_code,
                    'remember_token' => $user->remember_token,
                    'confirmed' => $user->confirmed,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'alternateFirstName' => $user->alternateFirstName,
                    'supervisorEmail' => $user->supervisorEmail,
                    'companyUserIdentifier' => $user->companyUserIdentifier,
                    'isSupervisor' => $user->isSupervisor,
                    'isValidator' => $user->isValidator,
                    'isActive' => $user->isActive,
                    'rgt' => $user->rgt,
                    'lft' => $user->lft,
                    'hierarchy' => $user->hierarchy,
                    'defaultLang' => $user->defaultLang,
                    'notes' => $user->notes,
                    'level' => $user->level,
                    'notify' => $user->notify,
                    'companyId' => $user->companyId,
                    'syncId' => $user->syncId,
                    'supervisorId' => $user->supervisorId,
                    'externalId' => $user->externalId,
                    'approverId' => $user->approverId,
                    'defaultLocationId' => $user->defaultLocationId
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'uuid',
                            'email',
                            'alternateEmail',
                            'password',
                            'username',
                            'confirmation_code',
                            'remember_token',
                            'confirmed',
                            'firstName',
                            'lastName',
                            'alternateFirstName',
                            'supervisorEmail',
                            'companyUserIdentifier',
                            'isSupervisor',
                            'isValidator',
                            'isActive',
                            'rgt',
                            'lft',
                            'hierarchy',
                            'defaultLang',
                            'notes',
                            'level',
                            'notify',
                            'companyId',
                            'syncId',
                            'supervisorId',
                            'externalId',
                            'approverId',
                            'defaultLocationId'
                        ],
                        'links' => [
                            'self'
                        ],
                        'relationships' => [
                            'assets' => [
                                'links' => [
                                    'self',
                                    'related'
                                ],
                                'data' => []
                            ]
                        ]
                    ]
                ]);
    }

    public function testCreateUserReturnRelationshipNoExistsInclude()
    {
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $companyDomain = factory(\WA\DataStore\Company\CompanyDomains::class)->create(['domain' => 'email.com', 'companyId' => $companyId]);

        $user = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);

        $res = $this->json('POST', '/users?include=assets',
            [
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'uuid' => $user->uuid,
                        'email' => 'user@email.com',
                        'alternateEmail' => $user->alternateEmail,
                        'password' => $user->password,
                        'username' => $user->username,
                        'confirmation_code' => $user->confirmation_code,
                        'remember_token' => $user->remember_token,
                        'confirmed' => $user->confirmed,
                        'firstName' => $user->firstName,
                        'lastName' => $user->lastName,
                        'alternateFirstName' => $user->alternateFirstName,
                        'supervisorEmail' => $user->supervisorEmail,
                        'companyUserIdentifier' => $user->companyUserIdentifier,
                        'isSupervisor' => $user->isSupervisor,
                        'isValidator' => $user->isValidator,
                        'isActive' => $user->isActive,
                        'rgt' => $user->rgt,
                        'lft' => $user->lft,
                        'hierarchy' => $user->hierarchy,
                        'defaultLang' => $user->defaultLang,
                        'notes' => $user->notes,
                        'level' => $user->level,
                        'notify' => $user->notify,
                        'companyId' => $user->companyId,
                        'syncId' => $user->syncId,
                        'supervisorId' => $user->supervisorId,
                        'externalId' => $user->externalId,
                        'approverId' => $user->approverId,
                        'defaultLocationId' => $user->defaultLocationId
                    ],
                    'relationships' => [
                        'NoAssets' => [
                            'data' => [
                                ['type' => 'assets', 'id' => 1],
                                ['type' => 'assets', 'id' => 2],
                            ],
                        ]
                    ],
                ],
            ]
            )
            //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJson(
                [
                    'uuid' => $user->uuid,
                    'alternateEmail' => $user->alternateEmail,
                    'username' => $user->username,
                    'confirmation_code' => $user->confirmation_code,
                    'remember_token' => $user->remember_token,
                    'confirmed' => $user->confirmed,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'alternateFirstName' => $user->alternateFirstName,
                    'supervisorEmail' => $user->supervisorEmail,
                    'companyUserIdentifier' => $user->companyUserIdentifier,
                    'isSupervisor' => $user->isSupervisor,
                    'isValidator' => $user->isValidator,
                    'isActive' => $user->isActive,
                    'rgt' => $user->rgt,
                    'lft' => $user->lft,
                    'hierarchy' => $user->hierarchy,
                    'defaultLang' => $user->defaultLang,
                    'notes' => $user->notes,
                    'level' => $user->level,
                    'notify' => $user->notify,
                    'companyId' => $user->companyId,
                    'syncId' => $user->syncId,
                    'supervisorId' => $user->supervisorId,
                    'externalId' => $user->externalId,
                    'approverId' => $user->approverId,
                    'defaultLocationId' => $user->defaultLocationId
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'uuid',
                            'email',
                            'alternateEmail',
                            'password',
                            'username',
                            'confirmation_code',
                            'remember_token',
                            'confirmed',
                            'firstName',
                            'lastName',
                            'alternateFirstName',
                            'supervisorEmail',
                            'companyUserIdentifier',
                            'isSupervisor',
                            'isValidator',
                            'isActive',
                            'rgt',
                            'lft',
                            'hierarchy',
                            'defaultLang',
                            'notes',
                            'level',
                            'notify',
                            'companyId',
                            'syncId',
                            'supervisorId',
                            'externalId',
                            'approverId',
                            'defaultLocationId'
                        ],
                        'links' => [
                            'self'
                        ],
                        'relationships' => [
                            'assets' => [
                                'links' => [
                                    'self',
                                    'related'
                                ],
                                'data' => []
                            ]
                        ]
                    ]
                ]);
    }

    public function testCreateUserReturnRelationshipNoData()
    {
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $companyDomain = factory(\WA\DataStore\Company\CompanyDomains::class)->create(['domain' => 'email.com', 'companyId' => $companyId]);

        $user = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);

        $res = $this->json('POST', '/users?include=assets',
            [
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'uuid' => $user->uuid,
                        'email' => 'user@email.com',
                        'alternateEmail' => $user->alternateEmail,
                        'password' => $user->password,
                        'username' => $user->username,
                        'confirmation_code' => $user->confirmation_code,
                        'remember_token' => $user->remember_token,
                        'confirmed' => $user->confirmed,
                        'firstName' => $user->firstName,
                        'lastName' => $user->lastName,
                        'alternateFirstName' => $user->alternateFirstName,
                        'supervisorEmail' => $user->supervisorEmail,
                        'companyUserIdentifier' => $user->companyUserIdentifier,
                        'isSupervisor' => $user->isSupervisor,
                        'isValidator' => $user->isValidator,
                        'isActive' => $user->isActive,
                        'rgt' => $user->rgt,
                        'lft' => $user->lft,
                        'hierarchy' => $user->hierarchy,
                        'defaultLang' => $user->defaultLang,
                        'notes' => $user->notes,
                        'level' => $user->level,
                        'notify' => $user->notify,
                        'companyId' => $user->companyId,
                        'syncId' => $user->syncId,
                        'supervisorId' => $user->supervisorId,
                        'externalId' => $user->externalId,
                        'approverId' => $user->approverId,
                        'defaultLocationId' => $user->defaultLocationId
                    ],
                    'relationships' => [
                        'assets' => [
                            'NoData' => [
                                ['type' => 'assets', 'id' => 1],
                                ['type' => 'assets', 'id' => 2],
                            ],
                        ]
                    ],
                ],
            ]
            )
            //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJson(
                [
                    'uuid' => $user->uuid,
                    'alternateEmail' => $user->alternateEmail,
                    'username' => $user->username,
                    'confirmation_code' => $user->confirmation_code,
                    'remember_token' => $user->remember_token,
                    'confirmed' => $user->confirmed,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'alternateFirstName' => $user->alternateFirstName,
                    'supervisorEmail' => $user->supervisorEmail,
                    'companyUserIdentifier' => $user->companyUserIdentifier,
                    'isSupervisor' => $user->isSupervisor,
                    'isValidator' => $user->isValidator,
                    'isActive' => $user->isActive,
                    'rgt' => $user->rgt,
                    'lft' => $user->lft,
                    'hierarchy' => $user->hierarchy,
                    'defaultLang' => $user->defaultLang,
                    'notes' => $user->notes,
                    'level' => $user->level,
                    'notify' => $user->notify,
                    'companyId' => $user->companyId,
                    'syncId' => $user->syncId,
                    'supervisorId' => $user->supervisorId,
                    'externalId' => $user->externalId,
                    'approverId' => $user->approverId,
                    'defaultLocationId' => $user->defaultLocationId
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'uuid',
                            'email',
                            'alternateEmail',
                            'password',
                            'username',
                            'confirmation_code',
                            'remember_token',
                            'confirmed',
                            'firstName',
                            'lastName',
                            'alternateFirstName',
                            'supervisorEmail',
                            'companyUserIdentifier',
                            'isSupervisor',
                            'isValidator',
                            'isActive',
                            'rgt',
                            'lft',
                            'hierarchy',
                            'defaultLang',
                            'notes',
                            'level',
                            'notify',
                            'companyId',
                            'syncId',
                            'supervisorId',
                            'externalId',
                            'approverId',
                            'defaultLocationId'
                        ],
                        'links' => [
                            'self'
                        ],
                        'relationships' => [
                            'assets' => [
                                'links' => [
                                    'self',
                                    'related'
                                ],
                                'data' => []
                            ]
                        ]
                    ]
                ]);
    }

    public function testCreateUserReturnRelationshipNoCorrectType()
    {
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $companyDomain = factory(\WA\DataStore\Company\CompanyDomains::class)->create(['domain' => 'email.com', 'companyId' => $companyId]);

        $user = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);

        $res = $this->json('POST', '/users?include=assets',
            [
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'uuid' => $user->uuid,
                        'email' => 'user@email.com',
                        'alternateEmail' => $user->alternateEmail,
                        'password' => $user->password,
                        'username' => $user->username,
                        'confirmation_code' => $user->confirmation_code,
                        'remember_token' => $user->remember_token,
                        'confirmed' => $user->confirmed,
                        'firstName' => $user->firstName,
                        'lastName' => $user->lastName,
                        'alternateFirstName' => $user->alternateFirstName,
                        'supervisorEmail' => $user->supervisorEmail,
                        'companyUserIdentifier' => $user->companyUserIdentifier,
                        'isSupervisor' => $user->isSupervisor,
                        'isValidator' => $user->isValidator,
                        'isActive' => $user->isActive,
                        'rgt' => $user->rgt,
                        'lft' => $user->lft,
                        'hierarchy' => $user->hierarchy,
                        'defaultLang' => $user->defaultLang,
                        'notes' => $user->notes,
                        'level' => $user->level,
                        'notify' => $user->notify,
                        'companyId' => $user->companyId,
                        'syncId' => $user->syncId,
                        'supervisorId' => $user->supervisorId,
                        'externalId' => $user->externalId,
                        'approverId' => $user->approverId,
                        'defaultLocationId' => $user->defaultLocationId
                    ],
                    'relationships' => [
                        'assets' => [
                            'data' => [
                                ['type' => 'NoAssets', 'id' => 1],
                                ['type' => 'NoAssets', 'id' => 2],
                            ],
                        ]
                    ],
                ],
            ]
            )
            //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJson(
                [
                    'uuid' => $user->uuid,
                    'alternateEmail' => $user->alternateEmail,
                    'username' => $user->username,
                    'confirmation_code' => $user->confirmation_code,
                    'remember_token' => $user->remember_token,
                    'confirmed' => $user->confirmed,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'alternateFirstName' => $user->alternateFirstName,
                    'supervisorEmail' => $user->supervisorEmail,
                    'companyUserIdentifier' => $user->companyUserIdentifier,
                    'isSupervisor' => $user->isSupervisor,
                    'isValidator' => $user->isValidator,
                    'isActive' => $user->isActive,
                    'rgt' => $user->rgt,
                    'lft' => $user->lft,
                    'hierarchy' => $user->hierarchy,
                    'defaultLang' => $user->defaultLang,
                    'notes' => $user->notes,
                    'level' => $user->level,
                    'notify' => $user->notify,
                    'companyId' => $user->companyId,
                    'syncId' => $user->syncId,
                    'supervisorId' => $user->supervisorId,
                    'externalId' => $user->externalId,
                    'approverId' => $user->approverId,
                    'defaultLocationId' => $user->defaultLocationId
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'uuid',
                            'email',
                            'alternateEmail',
                            'password',
                            'username',
                            'confirmation_code',
                            'remember_token',
                            'confirmed',
                            'firstName',
                            'lastName',
                            'alternateFirstName',
                            'supervisorEmail',
                            'companyUserIdentifier',
                            'isSupervisor',
                            'isValidator',
                            'isActive',
                            'rgt',
                            'lft',
                            'hierarchy',
                            'defaultLang',
                            'notes',
                            'level',
                            'notify',
                            'companyId',
                            'syncId',
                            'supervisorId',
                            'externalId',
                            'approverId',
                            'defaultLocationId'
                        ],
                        'links' => [
                            'self'
                        ],
                        'relationships' => [
                            'assets' => [
                                'links' => [
                                    'self',
                                    'related'
                                ],
                                'data' => []
                            ]
                        ]
                    ]
                ]);
    }

    public function testCreateUserReturnRelationshipNoIdExists()
    {
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $companyDomain = factory(\WA\DataStore\Company\CompanyDomains::class)->create(['domain' => 'email.com', 'companyId' => $companyId]);

        $user = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);

        $res = $this->json('POST', '/users?include=assets',
            [
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'uuid' => $user->uuid,
                        'email' => 'user@email.com',
                        'alternateEmail' => $user->alternateEmail,
                        'password' => $user->password,
                        'username' => $user->username,
                        'confirmation_code' => $user->confirmation_code,
                        'remember_token' => $user->remember_token,
                        'confirmed' => $user->confirmed,
                        'firstName' => $user->firstName,
                        'lastName' => $user->lastName,
                        'alternateFirstName' => $user->alternateFirstName,
                        'supervisorEmail' => $user->supervisorEmail,
                        'companyUserIdentifier' => $user->companyUserIdentifier,
                        'isSupervisor' => $user->isSupervisor,
                        'isValidator' => $user->isValidator,
                        'isActive' => $user->isActive,
                        'rgt' => $user->rgt,
                        'lft' => $user->lft,
                        'hierarchy' => $user->hierarchy,
                        'defaultLang' => $user->defaultLang,
                        'notes' => $user->notes,
                        'level' => $user->level,
                        'notify' => $user->notify,
                        'companyId' => $user->companyId,
                        'syncId' => $user->syncId,
                        'supervisorId' => $user->supervisorId,
                        'externalId' => $user->externalId,
                        'approverId' => $user->approverId,
                        'defaultLocationId' => $user->defaultLocationId
                    ],
                    'relationships' => [
                        'assets' => [
                            'data' => [
                                ['type' => 'assets', 'NoId' => 1],
                                ['type' => 'assets', 'NoId' => 2],
                            ],
                        ]
                    ]
                ],
            ]
            )
            //Log::debug("Users/id: ".print_r($res->response->getContent(), true));
            ->seeJson(
                [
                    'uuid' => $user->uuid,
                    'alternateEmail' => $user->alternateEmail,
                    'username' => $user->username,
                    'confirmation_code' => $user->confirmation_code,
                    'remember_token' => $user->remember_token,
                    'confirmed' => $user->confirmed,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'alternateFirstName' => $user->alternateFirstName,
                    'supervisorEmail' => $user->supervisorEmail,
                    'companyUserIdentifier' => $user->companyUserIdentifier,
                    'isSupervisor' => $user->isSupervisor,
                    'isValidator' => $user->isValidator,
                    'isActive' => $user->isActive,
                    'rgt' => $user->rgt,
                    'lft' => $user->lft,
                    'hierarchy' => $user->hierarchy,
                    'defaultLang' => $user->defaultLang,
                    'notes' => $user->notes,
                    'level' => $user->level,
                    'notify' => $user->notify,
                    'companyId' => $user->companyId,
                    'syncId' => $user->syncId,
                    'supervisorId' => $user->supervisorId,
                    'externalId' => $user->externalId,
                    'approverId' => $user->approverId,
                    'defaultLocationId' => $user->defaultLocationId
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'uuid',
                            'email',
                            'alternateEmail',
                            'password',
                            'username',
                            'confirmation_code',
                            'remember_token',
                            'confirmed',
                            'firstName',
                            'lastName',
                            'alternateFirstName',
                            'supervisorEmail',
                            'companyUserIdentifier',
                            'isSupervisor',
                            'isValidator',
                            'isActive',
                            'rgt',
                            'lft',
                            'hierarchy',
                            'defaultLang',
                            'notes',
                            'level',
                            'notify',
                            'companyId',
                            'syncId',
                            'supervisorId',
                            'externalId',
                            'approverId',
                            'defaultLocationId'
                        ],
                        'links' => [
                            'self'
                        ],
                        'relationships' => [
                            'assets' => [
                                'links' => [
                                    'self',
                                    'related'
                                ],
                                'data' => []
                            ]
                        ]
                    ]
                ]);
    }

    public function testUpdateUser()
    {
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $user1 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);
        $user2 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);
        
        $res = $this->json('PATCH', '/users/'.$user2->id,
            [
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'uuid' => $user1->uuid,
                        'email' => $user1->email,
                        'alternateEmail' => $user1->alternateEmail,
                        'password' => $user1->password,
                        'username' => $user1->username,
                        'confirmation_code' => $user1->confirmation_code,
                        'remember_token' => $user1->remember_token,
                        'confirmed' => $user1->confirmed,
                        'firstName' => $user1->firstName,
                        'lastName' => $user1->lastName,
                        'alternateFirstName' => $user1->alternateFirstName,
                        'supervisorEmail' => $user1->supervisorEmail,
                        'companyUserIdentifier' => $user1->companyUserIdentifier,
                        'isSupervisor' => $user1->isSupervisor,
                        'isValidator' => $user1->isValidator,
                        'isActive' => $user1->isActive,
                        'rgt' => $user1->rgt,
                        'lft' => $user1->lft,
                        'hierarchy' => $user1->hierarchy,
                        'defaultLang' => $user1->defaultLang,
                        'notes' => $user1->notes,
                        'level' => $user1->level,
                        'notify' => $user1->notify,
                        'companyId' => $user1->companyId,
                        'syncId' => $user1->syncId,
                        'supervisorId' => $user1->supervisorId,
                        'externalId' => $user1->externalId,
                        'approverId' => $user1->approverId,
                        'defaultLocationId' => $user1->defaultLocationId
                    ]
                ]
            ]
            )
            //Log::debug("RES TEST: ".print_r($res->response->getContent(), true));
            ->seeJson(
                [
                    'uuid' => $user1->uuid,
                    'email' => $user1->email,
                    'alternateEmail' => $user1->alternateEmail,
                    'username' => $user1->username,
                    'confirmation_code' => $user1->confirmation_code,
                    'remember_token' => $user1->remember_token,
                    'confirmed' => $user1->confirmed,
                    'firstName' => $user1->firstName,
                    'lastName' => $user1->lastName,
                    'alternateFirstName' => $user1->alternateFirstName,
                    'supervisorEmail' => $user1->supervisorEmail,
                    'companyUserIdentifier' => $user1->companyUserIdentifier,
                    'isSupervisor' => $user1->isSupervisor,
                    'isValidator' => $user1->isValidator,
                    'isActive' => $user1->isActive,
                    'rgt' => $user1->rgt,
                    'lft' => $user1->lft,
                    'hierarchy' => $user1->hierarchy,
                    'defaultLang' => $user1->defaultLang,
                    'notes' => $user1->notes,
                    'level' => $user1->level,
                    'notify' => $user1->notify,
                    'companyId' => $user1->companyId,
                    'syncId' => $user1->syncId,
                    'supervisorId' => $user1->supervisorId,
                    'externalId' => $user1->externalId,
                    'approverId' => $user1->approverId,
                    'defaultLocationId' => $user1->defaultLocationId
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'uuid',
                            'email',
                            'alternateEmail',
                            'password',
                            'username',
                            'confirmation_code',
                            'remember_token',
                            'confirmed',
                            'firstName',
                            'lastName',
                            'alternateFirstName',
                            'supervisorEmail',
                            'companyUserIdentifier',
                            'isSupervisor',
                            'isValidator',
                            'isActive',
                            'rgt',
                            'lft',
                            'hierarchy',
                            'defaultLang',
                            'notes',
                            'level',
                            'notify',
                            'companyId',
                            'syncId',
                            'supervisorId',
                            'externalId',
                            'approverId',
                            'defaultLocationId'
                        ],
                        'links' => [
                            'self'
                        ]
                    ]
                ]);
    }

    public function testUpdateUserIncludeAllDeleteRelationships()
    {
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $user = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);

        // ADDRESS
        $addressId = factory(\WA\DataStore\Address\Address::class)->create()->id;
 
        // ASSETS
/*
        $asset1 = factory(\WA\DataStore\Asset\Asset::class)->create(['userId' => $user->id]);
        $asset2 = factory(\WA\DataStore\Asset\Asset::class)->create(['userId' => $user->id]);

        $userAssetDB = DB::table('assets')->where('userId', $user->id)->get();
        $this->assertCount(2, $userAssetDB);
        $this->assertEquals($userAssetDB[0]->id, $asset1->id);
        $this->assertEquals($userAssetDB[1]->id, $asset2->id);
        $asset1DB = DB::table('assets')->where('id', $asset1->id)->get()[0];
        $asset2DB = DB::table('assets')->where('id', $asset2->id)->get()[0];
        
        $this->assertEquals($asset1DB->id, $asset1->id);
        $this->assertEquals($asset1DB->identification, $asset1->identification);
        $this->assertEquals($asset1DB->active, $asset1->active);
        $this->assertEquals($asset1DB->externalId, $asset1->externalId);
        $this->assertEquals($asset1DB->typeId, $asset1->typeId);
        $this->assertEquals($asset1DB->carrierId, $asset1->carrierId);
        $this->assertEquals($asset1DB->statusId, $asset1->statusId);
        $this->assertEquals($asset1DB->syncId, $asset1->syncId);

        $this->assertEquals($asset2DB->id, $asset2->id);
        $this->assertEquals($asset2DB->identification, $asset2->identification);
        $this->assertEquals($asset2DB->active, $asset2->active);
        $this->assertEquals($asset2DB->externalId, $asset2->externalId);
        $this->assertEquals($asset2DB->typeId, $asset2->typeId);
        $this->assertEquals($asset2DB->carrierId, $asset2->carrierId);
        $this->assertEquals($asset2DB->statusId, $asset2->statusId);
        $this->assertEquals($asset2DB->syncId, $asset2->syncId);
*/
        // DEVICE VARIATIONS       
        $devicevariation1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create();
        $devicevariation2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create();
        $arrayD = array($devicevariation1->id, $devicevariation2->id);
        $user->devicevariations()->sync($arrayD);

        $userDeviceDB = DB::table('user_device_variations')->where('userId', $user->id)->get();
        $this->assertCount(2, $userDeviceDB);
        $this->assertEquals($userDeviceDB[0]->deviceVariationId, $devicevariation1->id);
        $this->assertEquals($userDeviceDB[1]->deviceVariationId, $devicevariation2->id);
        
        $deviceVar1DB = DB::table('device_variations')->where('id', $devicevariation1->id)->get()[0];
        $deviceVar2DB = DB::table('device_variations')->where('id', $devicevariation2->id)->get()[0];
        $this->assertEquals($deviceVar1DB->id, $devicevariation1->id);
        $this->assertEquals($deviceVar1DB->priceRetail, $devicevariation1->priceRetail);
        $this->assertEquals($deviceVar1DB->price1, $devicevariation1->price1);
        $this->assertEquals($deviceVar1DB->price2, $devicevariation1->price2);
        $this->assertEquals($deviceVar1DB->priceOwn, $devicevariation1->priceOwn);
        $this->assertEquals($deviceVar1DB->deviceId, $devicevariation1->deviceId);
        $this->assertEquals($deviceVar1DB->carrierId, $devicevariation1->carrierId);
        $this->assertEquals($deviceVar1DB->companyId, $devicevariation1->companyId);
        $this->assertEquals($deviceVar2DB->id, $devicevariation2->id);
        $this->assertEquals($deviceVar2DB->priceRetail, $devicevariation2->priceRetail);
        $this->assertEquals($deviceVar2DB->price1, $devicevariation2->price1);
        $this->assertEquals($deviceVar2DB->price2, $devicevariation2->price2);
        $this->assertEquals($deviceVar2DB->priceOwn, $devicevariation2->priceOwn);
        $this->assertEquals($deviceVar2DB->deviceId, $devicevariation2->deviceId);
        $this->assertEquals($deviceVar2DB->carrierId, $devicevariation2->carrierId);
        $this->assertEquals($deviceVar2DB->companyId, $devicevariation2->companyId);
        
        // ROLES
        $role1 = factory(\WA\DataStore\Role\Role::class)->create();
        $role2 = factory(\WA\DataStore\Role\Role::class)->create();
        $arrayR = array($role1->id, $role2->id);
        $user->roles()->sync($arrayR);
        $userRoleDB = DB::table('role_user')->where('user_id', $user->id)->get();
        $this->assertCount(2, $userRoleDB);
        $this->assertEquals($userRoleDB[0]->role_id, $role1->id);
        $this->assertEquals($userRoleDB[1]->role_id, $role2->id);
        $role1DB = DB::table('roles')->where('id', $role1->id)->get()[0];
        $role2DB = DB::table('roles')->where('id', $role2->id)->get()[0];
        
        $this->assertEquals($role1DB->id, $role1->id);
        $this->assertEquals($role1DB->name, $role1->name);
        $this->assertEquals($role1DB->display_name, $role1->display_name);
        $this->assertEquals($role1DB->description, $role1->description);
        $this->assertEquals($role2DB->id, $role2->id);
        $this->assertEquals($role2DB->name, $role2->name);
        $this->assertEquals($role2DB->display_name, $role2->display_name);
        $this->assertEquals($role2DB->description, $role2->description);

        // UDLVALUES
        $udl1 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $companyId]);
        $udl2 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $companyId]);
        $udlV1 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['udlId' => $udl1->id]);
        $udlV2 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['udlId' => $udl2->id]);
        $arrayU = array($udlV1->id, $udlV2->id);
        $user->udlValues()->sync($arrayU);

        $userUdlDB = DB::table('user_udls')->where('userId', $user->id)->get();

        $this->assertCount(2, $userUdlDB);
        $this->assertEquals($userUdlDB[0]->udlValueId, $udlV1->id);
        $this->assertEquals($userUdlDB[1]->udlValueId, $udlV2->id);
        $udlV1DB = DB::table('udl_values')->where('id', $udlV1->id)->get()[0];
        $udlV2DB = DB::table('udl_values')->where('id', $udlV2->id)->get()[0];
        
        $this->assertEquals($udlV1DB->id, $udlV1->id);
        $this->assertEquals($udlV1DB->name, $udlV1->name);
        $this->assertEquals($udlV1DB->udlId, $udlV1->udlId);
        $this->assertEquals($udlV1DB->externalId, $udlV1->externalId);
        $this->assertEquals($udlV2DB->id, $udlV2->id);
        $this->assertEquals($udlV2DB->name, $udlV2->name);
        $this->assertEquals($udlV2DB->udlId, $udlV2->udlId);
        $this->assertEquals($udlV2DB->externalId, $udlV2->externalId);

        // ALLOCATIONS
        $carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        $allocation1 = factory(\WA\DataStore\Allocation\Allocation::class)->create(['userId' => $user->id]);
        $allocation1->carriers()->associate($carrier);
        $allocation1->save();
        $allocation2 = factory(\WA\DataStore\Allocation\Allocation::class)->create(['userId' => $user->id]);

        $allocation2->carriers()->associate($carrier);
        $allocation2->save();

        $content1 = factory(\WA\DataStore\Content\Content::class)->create(['owner_id' => $user->id, 'owner_type' => 'users']);
        $content2 = factory(\WA\DataStore\Content\Content::class)->create(['owner_id' => $user->id, 'owner_type' => 'users']);

        // assets include deleted.
        $res = $this->json('PATCH', '/users/'.$user->id.'?include=devicevariations,roles,udls,allocations,companies,contents,addresses',
            [
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'uuid' => $user->uuid,
                        'email' => $user->email,
                        'alternateEmail' => $user->alternateEmail,
                        'password' => $user->password,
                        'username' => $user->username,
                        'confirmation_code' => $user->confirmation_code,
                        'remember_token' => $user->remember_token,
                        'confirmed' => $user->confirmed,
                        'firstName' => $user->firstName,
                        'lastName' => $user->lastName,
                        'alternateFirstName' => $user->alternateFirstName,
                        'supervisorEmail' => $user->supervisorEmail,
                        'companyUserIdentifier' => $user->companyUserIdentifier,
                        'isSupervisor' => $user->isSupervisor,
                        'isValidator' => $user->isValidator,
                        'isActive' => $user->isActive,
                        'rgt' => $user->rgt,
                        'lft' => $user->lft,
                        'hierarchy' => $user->hierarchy,
                        'defaultLang' => $user->defaultLang,
                        'notes' => $user->notes,
                        'level' => $user->level,
                        'notify' => $user->notify,
                        'companyId' => $user->companyId,
                        'syncId' => $user->syncId,
                        'supervisorId' => $user->supervisorId,
                        'externalId' => $user->externalId,
                        'approverId' => $user->approverId,
                        'defaultLocationId' => $user->defaultLocationId
                    ],
                    'relationships' => [
                        'addresses' => [
                            'data' => [
                                ['type' => 'addresses', 'id' => $addressId]
                            ],
                        ],
/*                
                        'assets' => [
                            'data' => [
                                ['type' => 'assets', 'id' => $asset1->id]
                            ],
                        ],
*/
                        'devicevariations' => [
                            'data' => [
                                ['type' => 'devicevariations', 'id' => $devicevariation1->id]
                            ],
                        ],
                        'roles' => [
                            'data' => [
                                ['type' => 'roles', 'id' => $role1->id]
                            ],
                        ],
                        'udls' => [
                            'data' => [
                                ['type' => 'udls', 'id' => $udlV1->id]
                            ],
                        ],
                        'allocations' => [
                            'data' => [
                                [
                                    'id' => $allocation1->id,
                                    'type' => 'allocations',
                                    'billMonth' => $allocation1->billMonth,
                                    'mobileNumber' => $allocation1->mobileNumber,
                                    'carrier' => $allocation1->carrier,
                                    'currency' => $allocation1->currency,
                                    'handsetModel' => $allocation1->handsetModel,
                                    'totalAllocatedCharge' => $allocation1->totalAllocatedCharge,
                                    'preAllocatedAmountDue' => $allocation1->preAllocatedAmountDue,
                                    'otherAdjustments' => $allocation1->otherAdjustments,
                                    'preAdjustedAccessCharge' => $allocation1->preAdjustedAccessCharge,
                                    'adjustedAccessCharge' => $allocation1->adjustedAccessCharge,
                                    'bBCharge' => $allocation1->bBCharge,
                                    'pDACharge' => $allocation1->pDACharge,
                                    'iPhoneCharge' => $allocation1->iPhoneCharge,
                                    'featuresCharge' => $allocation1->featuresCharge,
                                    'dataCardCharge' => $allocation1->dataCardCharge,
                                    'lDCanadaCharge' => $allocation1->lDCanadaCharge,
                                    'uSAddOnPlanCharge' => $allocation1->uSAddOnPlanCharge,
                                    'uSLDAddOnPlanCharge' => $allocation1->uSAddOnPlanCharge,
                                    'uSDataRoamingCharge' => $allocation1->uSDataRoamingCharge,
                                    'nightAndWeekendAddOnCharge' => $allocation1->nightAndWeekendAddOnCharge,
                                    'minuteAddOnCharge' => $allocation1->minuteAddOnCharge,
                                    'servicePlanCharge' => $allocation1->servicePlanCharge,
                                    'directConnectCharge' => $allocation1->directConnectCharge,
                                    'textMessagingCharge' => $allocation1->textMessagingCharge,
                                    'dataCharge' => $allocation1->dataCharge,
                                    'intlRoamingCharge' => $allocation1->intlRoamingCharge,
                                    'intlLongDistanceCharge' => $allocation1->intlLongDistanceCharge,
                                    'directoryAssistanceCharge' => $allocation1->directoryAssistanceCharge,
                                    'callForwardingCharge' => $allocation1->callForwardingCharge,
                                    'airtimeCharge' => $allocation1->airtimeCharge,
                                    'usageCharge' => $allocation1->usageCharge,
                                    'equipmentCharge' => $allocation1->equipmentCharge,
                                    'otherDiscountCharge' => $allocation1->otherDiscountCharge,
                                    'taxesCharge' => $allocation1->taxesCharge,
                                    'thirdPartyCharge' => $allocation1->thirdPartyCharge,
                                    'otherCharge' => $allocation1->otherCharge,
                                    'waFees' => $allocation1->waFees,
                                    'lineFees' => $allocation1->lineFees,
                                    'mobilityFees' => $allocation1->mobilityFees,
                                    'feesCharge' => $allocation1->feesCharge,
                                    'last_upgrade' => $allocation1->last_upgrade
                                ]
                            ]
                        ],
                        'contents' => [
                            'data' => [
                                [
                                    'id' => $content1->id,
                                    'type' => 'contents',
                                    'content' => $content1->content,
                                    'active' => $content1->active,
                                    'owner_type' => $content1->owner_type
                                ]
                            ]
                        ]
                    ],
                ],
            ]
            )
            //Log::debug("testUpdateUserIncludeAllDeleteRelationships: ".print_r($res->response->getContent(), true));
            ->seeJson(
                [
                    'uuid' => $user->uuid,
                    'email' => $user->email,
                    'alternateEmail' => $user->alternateEmail,
                    'username' => $user->username,
                    'confirmation_code' => $user->confirmation_code,
                    'remember_token' => $user->remember_token,
                    'confirmed' => $user->confirmed,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'alternateFirstName' => $user->alternateFirstName,
                    'supervisorEmail' => $user->supervisorEmail,
                    'companyUserIdentifier' => $user->companyUserIdentifier,
                    'isSupervisor' => $user->isSupervisor,
                    'isValidator' => $user->isValidator,
                    'isActive' => $user->isActive,
                    'rgt' => $user->rgt,
                    'lft' => $user->lft,
                    'hierarchy' => $user->hierarchy,
                    'defaultLang' => $user->defaultLang,
                    'notes' => $user->notes,
                    'level' => $user->level,
                    'notify' => $user->notify,
                    'companyId' => $user->companyId,
                    'syncId' => $user->syncId,
                    'supervisorId' => $user->supervisorId,
                    'externalId' => $user->externalId,
                    'approverId' => $user->approverId,
                    'defaultLocationId' => $user->defaultLocationId
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'uuid',
                            'email',
                            'alternateEmail',
                            'password',
                            'username',
                            'confirmation_code',
                            'remember_token',
                            'confirmed',
                            'firstName',
                            'lastName',
                            'alternateFirstName',
                            'supervisorEmail',
                            'companyUserIdentifier',
                            'isSupervisor',
                            'isValidator',
                            'isActive',
                            'rgt',
                            'lft',
                            'hierarchy',
                            'defaultLang',
                            'notes',
                            'level',
                            'notify',
                            'companyId',
                            'syncId',
                            'supervisorId',
                            'externalId',
                            'approverId',
                            'defaultLocationId'
                        ],
                        'links' => [
                            'self'
                        ],
                        'relationships' => [
/*    
                            'assets' => [
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
                                    ]
                                ]
                            ],
*/
                            'devicevariations' => [
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
                            'companies' => [
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
                            'roles' => [
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
                            'allocations' => [
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
                            'contents' => [
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
                            'udls' => [
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
                            'addresses' => [
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
/*
                        0 => [ // ASSETS
                            'type',
                            'id',
                            'attributes' => [
                                'identification',
                                'active',
                                'statusId',
                                'typeId',
                                'externalId',
                                'carrierId',
                                'syncId'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
*/
                        0 => [ // COMPANIES
                            'type',
                            'id',
                            'attributes' => [
                                'name',
                                'label',
                                'active',
                                'udlpath',
                                'isCensus',
                                'udlPathRule',
                                'assetPath',
                                'currentBillMonth'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        1 => [ // DEVICEVARIATIONS
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
                        2 => [ // ROLES
                            'type',
                            'id',
                            'attributes' => [
                                'name'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        3 => [ // ALLOCATIONS
                            'type',
                            'id',
                            'attributes' => [
                                'bill_month',
                                'carrier',
                                'mobile_number',
                                'currency',
                                'device',
                                'allocated_charge',
                                'service_plan_charge',
                                'usage_charge',
                                'other_charge',
                                'fees_charge',
                                'last_upgrade'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        4 => [ // CONTENTS
                            'type',
                            'id',
                            'attributes' => [
                                'content',
                                'active',
                                'owner_type',
                                'owner_id'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        5 => [ // UDLVALUES
                            'type',
                            'id',
                            'attributes' => [
                                'udlId',
                                'udlValue'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        6 => [ // ADDRESS
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
                                'self'
                            ]
                        ]
                    ]
                ]);
    }

    public function testUpdateUserIncludeAllAddRelationships()
    {
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $user = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);
        $userOther = factory(\WA\DataStore\User\User::class)->create(['companyId' => $companyId]);

        // ADDRESS
        $addressId = factory(\WA\DataStore\Address\Address::class)->create()->id;
        $user->addresses()->sync([$addressId]);
 
        // ASSET
        $asset1 = factory(\WA\DataStore\Asset\Asset::class)->create(['userId' => $user->id])->id;
        $asset2 = factory(\WA\DataStore\Asset\Asset::class)->create(['userId' => $user->id])->id;
        $asset3 = factory(\WA\DataStore\Asset\Asset::class)->create(['userId' => $userOther->id])->id;
        
        // DEVICE VARIATIONS
        $deviceVariation1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;
        $deviceVariation2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;
        $arrayD = array($deviceVariation1, $deviceVariation2);
        $user->devicevariations()->sync($arrayD);
        $deviceVariation3 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;
        
        // ROLES
        $role1 = factory(\WA\DataStore\Role\Role::class)->create()->id;
        $role2 = factory(\WA\DataStore\Role\Role::class)->create()->id;
        $arrayR = array($role1, $role2);
        $user->roles()->sync($arrayR);
        $role3 = factory(\WA\DataStore\Role\Role::class)->create()->id;
        
        // UDL VALUES
        $udl1 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $companyId])->id;
        $udl2 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $companyId])->id;
        $udlV1 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['udlId' => $udl1])->id;
        $udlV2 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['udlId' => $udl2])->id;
        $arrayU = array($udlV1, $udlV2);
        $user->udlValues()->sync($arrayU);
        $udl3 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $companyId])->id;
        $udlV3 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create(['udlId' => $udl2])->id;

        // CARRIER
        $carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();

        // ALLOCATION
        $allocation1 = factory(\WA\DataStore\Allocation\Allocation::class)->create(['userId' => $user->id]);
        $allocation1->carriers()->associate($carrier);
        $allocation1->save();
        $allocation2 = factory(\WA\DataStore\Allocation\Allocation::class)->create(['userId' => $user->id]);
        $allocation2->carriers()->associate($carrier);
        $allocation2->save();
        
        // CONTENT
        $content1 = factory(\WA\DataStore\Content\Content::class)->create(['owner_id' => $user->id, 'owner_type' => 'users']);
        $content2 = factory(\WA\DataStore\Content\Content::class)->create(['owner_id' => $user->id, 'owner_type' => 'users']);
        
        // assets include deleted.
        $res = $this->json('PATCH', '/users/'.$user->id.'?include=devicevariations,roles,udls,allocations,companies,contents,addresses',
            [
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'uuid' => $user->uuid,
                        'email' => $user->email,
                        'alternateEmail' => $user->alternateEmail,
                        'password' => $user->password,
                        'username' => $user->username,
                        'confirmation_code' => $user->confirmation_code,
                        'remember_token' => $user->remember_token,
                        'confirmed' => $user->confirmed,
                        'firstName' => $user->firstName,
                        'lastName' => $user->lastName,
                        'alternateFirstName' => $user->alternateFirstName,
                        'supervisorEmail' => $user->supervisorEmail,
                        'companyUserIdentifier' => $user->companyUserIdentifier,
                        'isSupervisor' => $user->isSupervisor,
                        'isValidator' => $user->isValidator,
                        'isActive' => $user->isActive,
                        'rgt' => $user->rgt,
                        'lft' => $user->lft,
                        'hierarchy' => $user->hierarchy,
                        'defaultLang' => $user->defaultLang,
                        'notes' => $user->notes,
                        'level' => $user->level,
                        'notify' => $user->notify,
                        'companyId' => $user->companyId,
                        'syncId' => $user->syncId,
                        'supervisorId' => $user->supervisorId,
                        'externalId' => $user->externalId,
                        'approverId' => $user->approverId,
                        'defaultLocationId' => $user->defaultLocationId
                    ],
                    'relationships' => [
                        'addresses' => [
                            'data' => [
                                ['type' => 'addresses', 'id' => $addressId]
                            ],
                        ],
/*                        
                        'assets' => [
                            'data' => [
                                ['type' => 'assets', 'id' => $asset1],
                                ['type' => 'assets', 'id' => $asset2],
                                ['type' => 'assets', 'id' => $asset3]
                            ],
                        ],
*/
                        'devicevariations' => [
                            'data' => [
                                ['type' => 'devicevariations', 'id' => $deviceVariation1],
                                ['type' => 'devicevariations', 'id' => $deviceVariation2],
                                ['type' => 'devicevariations', 'id' => $deviceVariation3]
                            ],
                        ],
                        'roles' => [
                            'data' => [
                                ['type' => 'roles', 'id' => $role1],
                                ['type' => 'roles', 'id' => $role2],
                                ['type' => 'roles', 'id' => $role3]
                            ],
                        ],
                        'udls' => [
                            'data' => [
                                ['type' => 'udls', 'id' => $udlV1],
                                ['type' => 'udls', 'id' => $udlV2],
                                ['type' => 'udls', 'id' => $udlV3]
                            ],
                        ],
                        'allocations' => [
                            'data' => [
                                [
                                    'id' => 1,
                                    'type' => 'allocations',
                                    'billMonth' => $allocation1->billMonth,
                                    'mobileNumber' => $allocation1->mobileNumber,
                                    'carrier' => $allocation1->carrier,
                                    'currency' => $allocation1->currency,
                                    'handsetModel' => $allocation1->handsetModel,
                                    'totalAllocatedCharge' => $allocation1->totalAllocatedCharge,
                                    'preAllocatedAmountDue' => $allocation1->preAllocatedAmountDue,
                                    'otherAdjustments' => $allocation1->otherAdjustments,
                                    'preAdjustedAccessCharge' => $allocation1->preAdjustedAccessCharge,
                                    'adjustedAccessCharge' => $allocation1->adjustedAccessCharge,
                                    'bBCharge' => $allocation1->bBCharge,
                                    'pDACharge' => $allocation1->pDACharge,
                                    'iPhoneCharge' => $allocation1->iPhoneCharge,
                                    'featuresCharge' => $allocation1->featuresCharge,
                                    'dataCardCharge' => $allocation1->dataCardCharge,
                                    'lDCanadaCharge' => $allocation1->lDCanadaCharge,
                                    'uSAddOnPlanCharge' => $allocation1->uSAddOnPlanCharge,
                                    'uSLDAddOnPlanCharge' => $allocation1->uSAddOnPlanCharge,
                                    'uSDataRoamingCharge' => $allocation1->uSDataRoamingCharge,
                                    'nightAndWeekendAddOnCharge' => $allocation1->nightAndWeekendAddOnCharge,
                                    'minuteAddOnCharge' => $allocation1->minuteAddOnCharge,
                                    'servicePlanCharge' => $allocation1->servicePlanCharge,
                                    'directConnectCharge' => $allocation1->directConnectCharge,
                                    'textMessagingCharge' => $allocation1->textMessagingCharge,
                                    'dataCharge' => $allocation1->dataCharge,
                                    'intlRoamingCharge' => $allocation1->intlRoamingCharge,
                                    'intlLongDistanceCharge' => $allocation1->intlLongDistanceCharge,
                                    'directoryAssistanceCharge' => $allocation1->directoryAssistanceCharge,
                                    'callForwardingCharge' => $allocation1->callForwardingCharge,
                                    'airtimeCharge' => $allocation1->airtimeCharge,
                                    'usageCharge' => $allocation1->usageCharge,
                                    'equipmentCharge' => $allocation1->equipmentCharge,
                                    'otherDiscountCharge' => $allocation1->otherDiscountCharge,
                                    'taxesCharge' => $allocation1->taxesCharge,
                                    'thirdPartyCharge' => $allocation1->thirdPartyCharge,
                                    'otherCharge' => $allocation1->otherCharge,
                                    'waFees' => $allocation1->waFees,
                                    'lineFees' => $allocation1->lineFees,
                                    'mobilityFees' => $allocation1->mobilityFees,
                                    'feesCharge' => $allocation1->feesCharge,
                                    'last_upgrade' => $allocation1->last_upgrade,
                                    'deviceType' => $allocation1->deviceType,
                                    'domesticUsageCharge' => $allocation1->domesticUsageCharge,
                                    'domesticDataUsage' => $allocation1->domesticDataUsage,
                                    'domesticVoiceUsage' => $allocation1->domesticVoiceUsage,
                                    'domesticTextUsage' => $allocation1->domesticTextUsage,
                                    'intlRoamUsageCharge' => $allocation1->intlRoamUsageCharge,
                                    'intlRoamDataUsage' => $allocation1->intlRoamDataUsage,
                                    'intlRoamVoiceUsage' => $allocation1->intlRoamVoiceUsage,
                                    'intlRoamTextUsage' => $allocation1->intlRoamTextUsage,
                                    'intlLDUsageCharge' => $allocation1->intlLDUsageCharge,
                                    'intlLDVoiceUsage' => $allocation1->intlLDVoiceUsage,
                                    'intlLDTextUsage' => $allocation1->intlLDTextUsage,
                                    'etfCharge' => $allocation1->etfCharge,
                                    'otherCarrierCharge' => $allocation1->otherCarrierCharge,
                                    'deviceEsnImei' => $allocation1->deviceEsnImei,
                                    'deviceSim' => $allocation1->deviceSim
                                ],
                                [
                                    'id' => 2,
                                    'type' => 'allocations',
                                    'billMonth' => $allocation2->billMonth,
                                    'mobileNumber' => $allocation2->mobileNumber,
                                    'carrier' => $allocation2->carrier,
                                    'currency' => $allocation2->currency,
                                    'handsetModel' => $allocation2->handsetModel,
                                    'totalAllocatedCharge' => $allocation2->totalAllocatedCharge,
                                    'preAllocatedAmountDue' => $allocation2->preAllocatedAmountDue,
                                    'otherAdjustments' => $allocation2->otherAdjustments,
                                    'preAdjustedAccessCharge' => $allocation2->preAdjustedAccessCharge,
                                    'adjustedAccessCharge' => $allocation2->adjustedAccessCharge,
                                    'bBCharge' => $allocation2->bBCharge,
                                    'pDACharge' => $allocation2->pDACharge,
                                    'iPhoneCharge' => $allocation2->iPhoneCharge,
                                    'featuresCharge' => $allocation2->featuresCharge,
                                    'dataCardCharge' => $allocation2->dataCardCharge,
                                    'lDCanadaCharge' => $allocation2->lDCanadaCharge,
                                    'uSAddOnPlanCharge' => $allocation2->uSAddOnPlanCharge,
                                    'uSLDAddOnPlanCharge' => $allocation2->uSAddOnPlanCharge,
                                    'uSDataRoamingCharge' => $allocation2->uSDataRoamingCharge,
                                    'nightAndWeekendAddOnCharge' => $allocation2->nightAndWeekendAddOnCharge,
                                    'minuteAddOnCharge' => $allocation2->minuteAddOnCharge,
                                    'servicePlanCharge' => $allocation2->servicePlanCharge,
                                    'directConnectCharge' => $allocation2->directConnectCharge,
                                    'textMessagingCharge' => $allocation2->textMessagingCharge,
                                    'dataCharge' => $allocation2->dataCharge,
                                    'intlRoamingCharge' => $allocation2->intlRoamingCharge,
                                    'intlLongDistanceCharge' => $allocation2->intlLongDistanceCharge,
                                    'directoryAssistanceCharge' => $allocation2->directoryAssistanceCharge,
                                    'callForwardingCharge' => $allocation2->callForwardingCharge,
                                    'airtimeCharge' => $allocation2->airtimeCharge,
                                    'usageCharge' => $allocation2->usageCharge,
                                    'equipmentCharge' => $allocation2->equipmentCharge,
                                    'otherDiscountCharge' => $allocation2->otherDiscountCharge,
                                    'taxesCharge' => $allocation2->taxesCharge,
                                    'thirdPartyCharge' => $allocation2->thirdPartyCharge,
                                    'otherCharge' => $allocation2->otherCharge,
                                    'waFees' => $allocation2->waFees,
                                    'lineFees' => $allocation2->lineFees,
                                    'mobilityFees' => $allocation2->mobilityFees,
                                    'feesCharge' => $allocation2->feesCharge,
                                    'last_upgrade' => $allocation2->last_upgrade,
                                    'deviceType' => $allocation2->deviceType,
                                    'domesticUsageCharge' => $allocation2->domesticUsageCharge,
                                    'domesticDataUsage' => $allocation2->domesticDataUsage,
                                    'domesticVoiceUsage' => $allocation2->domesticVoiceUsage,
                                    'domesticTextUsage' => $allocation2->domesticTextUsage,
                                    'intlRoamUsageCharge' => $allocation2->intlRoamUsageCharge,
                                    'intlRoamDataUsage' => $allocation2->intlRoamDataUsage,
                                    'intlRoamVoiceUsage' => $allocation2->intlRoamVoiceUsage,
                                    'intlRoamTextUsage' => $allocation2->intlRoamTextUsage,
                                    'intlLDUsageCharge' => $allocation2->intlLDUsageCharge,
                                    'intlLDVoiceUsage' => $allocation2->intlLDVoiceUsage,
                                    'intlLDTextUsage' => $allocation2->intlLDTextUsage,
                                    'etfCharge' => $allocation2->etfCharge,
                                    'otherCarrierCharge' => $allocation2->otherCarrierCharge,
                                    'deviceEsnImei' => $allocation2->deviceEsnImei,
                                    'deviceSim' => $allocation2->deviceSim
                                ],
                                [
                                    'id' => 0,
                                    'type' => 'allocations',
                                    'billMonth' => $allocation1->billMonth,
                                    'mobileNumber' => $allocation1->mobileNumber,
                                    'carrier' => $allocation1->carrier,
                                    'currency' => $allocation1->currency,
                                    'handsetModel' => $allocation1->handsetModel,
                                    'totalAllocatedCharge' => $allocation1->totalAllocatedCharge,
                                    'preAllocatedAmountDue' => $allocation1->preAllocatedAmountDue,
                                    'otherAdjustments' => $allocation1->otherAdjustments,
                                    'preAdjustedAccessCharge' => $allocation1->preAdjustedAccessCharge,
                                    'adjustedAccessCharge' => $allocation1->adjustedAccessCharge,
                                    'bBCharge' => $allocation1->bBCharge,
                                    'pDACharge' => $allocation1->pDACharge,
                                    'iPhoneCharge' => $allocation1->iPhoneCharge,
                                    'featuresCharge' => $allocation1->featuresCharge,
                                    'dataCardCharge' => $allocation1->dataCardCharge,
                                    'lDCanadaCharge' => $allocation1->lDCanadaCharge,
                                    'uSAddOnPlanCharge' => $allocation1->uSAddOnPlanCharge,
                                    'uSLDAddOnPlanCharge' => $allocation1->uSAddOnPlanCharge,
                                    'uSDataRoamingCharge' => $allocation1->uSDataRoamingCharge,
                                    'nightAndWeekendAddOnCharge' => $allocation1->nightAndWeekendAddOnCharge,
                                    'minuteAddOnCharge' => $allocation1->minuteAddOnCharge,
                                    'servicePlanCharge' => $allocation1->servicePlanCharge,
                                    'directConnectCharge' => $allocation2->directConnectCharge,
                                    'textMessagingCharge' => $allocation2->textMessagingCharge,
                                    'dataCharge' => $allocation2->dataCharge,
                                    'intlRoamingCharge' => $allocation2->intlRoamingCharge,
                                    'intlLongDistanceCharge' => $allocation2->intlLongDistanceCharge,
                                    'directoryAssistanceCharge' => $allocation2->directoryAssistanceCharge,
                                    'callForwardingCharge' => $allocation2->callForwardingCharge,
                                    'airtimeCharge' => $allocation2->airtimeCharge,
                                    'usageCharge' => $allocation2->usageCharge,
                                    'equipmentCharge' => $allocation2->equipmentCharge,
                                    'otherDiscountCharge' => $allocation2->otherDiscountCharge,
                                    'taxesCharge' => $allocation2->taxesCharge,
                                    'thirdPartyCharge' => $allocation2->thirdPartyCharge,
                                    'otherCharge' => $allocation2->otherCharge,
                                    'waFees' => $allocation2->waFees,
                                    'lineFees' => $allocation2->lineFees,
                                    'mobilityFees' => $allocation2->mobilityFees,
                                    'feesCharge' => $allocation2->feesCharge,
                                    'last_upgrade' => $allocation2->last_upgrade,
                                    'deviceType' => $allocation2->deviceType,
                                    'domesticUsageCharge' => $allocation2->domesticUsageCharge,
                                    'domesticDataUsage' => $allocation2->domesticDataUsage,
                                    'domesticVoiceUsage' => $allocation2->domesticVoiceUsage,
                                    'domesticTextUsage' => $allocation2->domesticTextUsage,
                                    'intlRoamUsageCharge' => $allocation2->intlRoamUsageCharge,
                                    'intlRoamDataUsage' => $allocation2->intlRoamDataUsage,
                                    'intlRoamVoiceUsage' => $allocation2->intlRoamVoiceUsage,
                                    'intlRoamTextUsage' => $allocation2->intlRoamTextUsage,
                                    'intlLDUsageCharge' => $allocation2->intlLDUsageCharge,
                                    'intlLDVoiceUsage' => $allocation2->intlLDVoiceUsage,
                                    'intlLDTextUsage' => $allocation2->intlLDTextUsage,
                                    'etfCharge' => $allocation2->etfCharge,
                                    'otherCarrierCharge' => $allocation2->otherCarrierCharge,
                                    'deviceEsnImei' => $allocation2->deviceEsnImei,
                                    'deviceSim' => $allocation2->deviceSim
                                ]
                            ]
                        ],
                        'contents' => [
                            'data' => [
                                [
                                    'id' => 1,
                                    'type' => 'contents',
                                    'content' => $content1->content,
                                    'active' => $content1->active,
                                    'owner_type' => $content1->owner_type
                                ],
                                [
                                    'id' => 2,
                                    'type' => 'contents',
                                    'content' => $content2->content,
                                    'active' => $content2->active,
                                    'owner_type' => $content2->owner_type
                                ],
                                [
                                    'id' => 0,
                                    'type' => 'contents',
                                    'content' => $content2->content,
                                    'active' => $content1->active,
                                    'owner_type' => $content2->owner_type
                                ]
                            ]
                        ]
                    ],
                ],
            ]
            )
            //Log::debug("testUpdateUserIncludeAllAddRelationships: ".print_r($res->response->getContent(), true));
            ->seeJson(
                [
                    'uuid' => $user->uuid,
                    'email' => $user->email,
                    'alternateEmail' => $user->alternateEmail,
                    'username' => $user->username,
                    'confirmation_code' => $user->confirmation_code,
                    'remember_token' => $user->remember_token,
                    'confirmed' => $user->confirmed,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'alternateFirstName' => $user->alternateFirstName,
                    'supervisorEmail' => $user->supervisorEmail,
                    'companyUserIdentifier' => $user->companyUserIdentifier,
                    'isSupervisor' => $user->isSupervisor,
                    'isValidator' => $user->isValidator,
                    'isActive' => $user->isActive,
                    'rgt' => $user->rgt,
                    'lft' => $user->lft,
                    'hierarchy' => $user->hierarchy,
                    'defaultLang' => $user->defaultLang,
                    'notes' => $user->notes,
                    'level' => $user->level,
                    'notify' => $user->notify,
                    'companyId' => $user->companyId,
                    'syncId' => $user->syncId,
                    'supervisorId' => $user->supervisorId,
                    'externalId' => $user->externalId,
                    'approverId' => $user->approverId,
                    'defaultLocationId' => $user->defaultLocationId
                ])
            ->seeJsonStructure(
                [
                    'data' => [
                        'type',
                        'id',
                        'attributes' => [
                            'uuid',
                            'identification',
                            'email',
                            'alternateEmail',
                            'password',
                            'username',
                            'confirmation_code',
                            'remember_token',
                            'confirmed',
                            'firstName',
                            'lastName',
                            'alternateFirstName',
                            'supervisorEmail',
                            'companyUserIdentifier',
                            'isSupervisor',
                            'isValidator',
                            'isActive',
                            'rgt',
                            'lft',
                            'hierarchy',
                            'defaultLang',
                            'notes',
                            'level',
                            'notify',
                            'companyId',
                            'syncId',
                            'supervisorId',
                            'externalId',
                            'approverId',
                            'defaultLocationId'
                        ],
                        'links' => [
                            'self'
                        ],
                        'relationships' => [
/*    
                            'assets' => [
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
*/
                            'devicevariations' => [
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
                            'companies' => [
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
                            'roles' => [
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
                            'allocations' => [
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
                            'contents' => [
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
                            'udls' => [
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
                            'addresses' => [
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
/*                    
                        0 => [ // ASSETS
                            'type',
                            'id',
                            'attributes' => [
                                'identification',
                                'active',
                                'statusId',
                                'typeId',
                                'externalId',
                                'carrierId',
                                'syncId'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        1 => [ // ASSETS
                            'type',
                            'id',
                            'attributes' => [
                                'identification',
                                'active',
                                'statusId',
                                'typeId',
                                'externalId',
                                'carrierId',
                                'syncId'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        2 => [ // ASSETS
                            'type',
                            'id',
                            'attributes' => [
                                'identification',
                                'active',
                                'statusId',
                                'typeId',
                                'externalId',
                                'carrierId',
                                'syncId'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
*/                        
                        0 => [ // COMPANIES
                            'type',
                            'id',
                            'attributes' => [
                                'name',
                                'label',
                                'active',
                                'udlpath',
                                'isCensus',
                                'udlPathRule',
                                'assetPath',
                                'shortName',
                                'currentBillMonth',
                                'defaultLocation'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        1 => [ // DEVICEVARIATIONS
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
                        2 => [ // DEVICEVARIATIONS
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
                        3 => [ // DEVICEVARIATIONS
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
                        4 => [ // ROLES
                            'type',
                            'id',
                            'attributes' => [
                                'name'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        5 => [ // ROLES
                            'type',
                            'id',
                            'attributes' => [
                                'name'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        6 => [ // ROLES
                            'type',
                            'id',
                            'attributes' => [
                                'name'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        7 => [ // ALLOCATIONS
                            'type',
                            'id',
                            'attributes' => [
                                'bill_month',
                                'carrier',
                                'mobile_number',
                                'currency',
                                'device',
                                'allocated_charge',
                                'service_plan_charge',
                                'usage_charge',
                                'other_charge',
                                'fees_charge',
                                'last_upgrade'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        8 => [ // ALLOCATIONS
                            'type',
                            'id',
                            'attributes' => [
                                'bill_month',
                                'carrier',
                                'mobile_number',
                                'currency',
                                'device',
                                'allocated_charge',
                                'service_plan_charge',
                                'usage_charge',
                                'other_charge',
                                'fees_charge',
                                'last_upgrade'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        9 => [ // ALLOCATIONS
                            'type',
                            'id',
                            'attributes' => [
                                'bill_month',
                                'carrier',
                                'mobile_number',
                                'currency',
                                'device',
                                'allocated_charge',
                                'service_plan_charge',
                                'usage_charge',
                                'other_charge',
                                'fees_charge',
                                'last_upgrade'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        10 => [ // CONTENTS
                            'type',
                            'id',
                            'attributes' => [
                                'content',
                                'active',
                                'owner_type',
                                'owner_id'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        11 => [ // CONTENTS
                            'type',
                            'id',
                            'attributes' => [
                                'content',
                                'active',
                                'owner_type',
                                'owner_id'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        12 => [ // CONTENTS
                            'type',
                            'id',
                            'attributes' => [
                                'content',
                                'active',
                                'owner_type',
                                'owner_id'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        13 => [ // UDLVALUES
                            'type',
                            'id',
                            'attributes' => [
                                'udlId',
                                'udlName',
                                'udlValue'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        14 => [ // UDLVALUES
                            'type',
                            'id',
                            'attributes' => [
                                'udlId',
                                'udlName',
                                'udlValue'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        15 => [ // UDLVALUES
                            'type',
                            'id',
                            'attributes' => [
                                'udlId',
                                'udlName',
                                'udlValue'
                            ],
                            'links' => [
                                'self'
                            ]
                        ],
                        16 => [ // ADDRESS
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
                                'self'
                            ]
                        ]
                    ]
                ]);
    }

    public function testDeleteUserIfExists()
    {
        // CREATE & DELETE
        $user = factory(\WA\DataStore\User\User::class)->create();
        $responseDel = $this->call('DELETE', '/users/'.$user->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', '/users/'.$user->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeleteUserIfNoExists()
    {
        // DELETE NO EXISTING.
        $responseDel = $this->call('DELETE', '/users/1');
        $this->assertEquals(404, $responseDel->status());

    }

    public function testUserPackagesUdlString()
    {
        // COMPANY
        $company = factory(\WA\DataStore\Company\Company::class)->create();

        // UDLS
        $udl1 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $company->id, 'name' => 'Name1']);
        $udl2 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $company->id, 'name' => 'Name2']);

        // UDLVALUES
        $udl1Value1 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create([
            'udlId' => $udl1->id,
            'name' => 'udl1Value1'
        ]);
        $udl1Value2 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create([
            'udlId' => $udl1->id,
            'name' => 'udl1Value2'
        ]);
        $udl1Value3 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create([
            'udlId' => $udl1->id,
            'name' => 'udl1Value3'
        ]);

        $user = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);

        $userUdlValue1 = factory(\WA\DataStore\User\UserUdlValue::class)->create([
            'userId' => $user->id,
            'udlValueId' => $udl1Value1->id
        ]);

        // PACKAGES
        $package1 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package1',
            'information' => 'Information1',
        ]);
        $package2 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package2',
            'information' => 'Information2',
        ]);
        $package3 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package3',
            'information' => 'Information3',
        ]);

        $package4 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package4',
            'information' => 'Information4',
        ]);
        $package5 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package5',
            'information' => 'Information5',
        ]);
        $package6 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package6',
            'information' => 'Information6',
        ]);
        $package7 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package7',
            'information' => 'Information7',
        ]);

        // CONDITIONS
        $pack1Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package1->id,
            'name' => 'Name1',
            'condition' => 'equals',
            'value' => 'udl1Value1'
        ]); // OK!

        $pack2Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package2->id,
            'name' => 'Name1',
            'condition' => 'contains',
            'value' => 'udl1'
        ]); // OK!

        $pack3Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package3->id,
            'name' => 'Name1',
            'condition' => 'not equals',
            'value' => 'udl2Value1'
        ]); // OK!

        $pack4Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package4->id,
            'name' => 'Name1',
            'condition' => 'equal',
            'value' => 'udl2Value1'
        ]); // NO!

        $pack5Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package5->id,
            'name' => 'Name1',
            'condition' => 'contains',
            'value' => 'udl2'
        ]); // NO!

        $pack6Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package6->id,
            'name' => 'Name1',
            'condition' => 'not equal',
            'value' => 'udl1Value1'
        ]); // NO!

        $res = $this->json('GET', '/users/packages/' . $user->id)
            ->seeJsonEquals([
                'data' => [
                    [
                        'id' => 1,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package1',
                            'information' => 'Information1',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 2,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package2',
                            'information' => 'Information2',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 3,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package3',
                            'information' => 'Information3',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 7,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package7',
                            'information' => 'Information7',
                            'companyId' => '1'
                        ]
                    ]
                ]
            ]);
    }

    public function testUserPackagesUdlNumber()
    {
        // COMPANY
        $company = factory(\WA\DataStore\Company\Company::class)->create();

        // UDLS
        $udl1 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $company->id, 'name' => 'Name1']);
        $udl2 = factory(\WA\DataStore\Udl\Udl::class)->create(['companyId' => $company->id, 'name' => 'Name2']);

        // UDLVALUES
        $udl1Value1 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create([
            'udlId' => $udl1->id,
            'name' => 1
        ]);
        $udl1Value2 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create([
            'udlId' => $udl1->id,
            'name' => 2
        ]);
        $udl1Value3 = factory(\WA\DataStore\UdlValue\UdlValue::class)->create([
            'udlId' => $udl1->id,
            'name' => 3
        ]);

        $user1 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);

        $userUdlValue1 = factory(\WA\DataStore\User\UserUdlValue::class)->create([
            'userId' => $user1->id,
            'udlValueId' => $udl1Value1->id
        ]);

        $user2 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);

        $userUdlValue1 = factory(\WA\DataStore\User\UserUdlValue::class)->create([
            'userId' => $user2->id,
            'udlValueId' => $udl1Value2->id
        ]);

        $user3 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);

        $userUdlValue1 = factory(\WA\DataStore\User\UserUdlValue::class)->create([
            'userId' => $user3->id,
            'udlValueId' => $udl1Value3->id
        ]);

        // PACKAGES
        $package1 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package1',
            'information' => 'Information1',
        ]);
        $package2 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package2',
            'information' => 'Information2',
        ]);
        $package3 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package3',
            'information' => 'Information3',
        ]);

        $package4 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package4',
            'information' => 'Information4',
        ]);
        $package5 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package5',
            'information' => 'Information5',
        ]);
        $package6 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package6',
            'information' => 'Information6',
        ]);
        $package7 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package7',
            'information' => 'Information7',
        ]);

        // CONDITIONS
        $pack1Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package1->id,
            'name' => 'Name1',
            'condition' => 'equal',
            'value' => 2
        ]);

        $pack2Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package2->id,
            'name' => 'Name1',
            'condition' => 'greater than',
            'value' => 2
        ]);

        $pack3Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package3->id,
            'name' => 'Name1',
            'condition' => 'less than',
            'value' => 2
        ]);

        $pack4Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package4->id,
            'name' => 'Name1',
            'condition' => 'greater or equal',
            'value' => 2
        ]);

        $pack5Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package5->id,
            'name' => 'Name1',
            'condition' => 'less or equal',
            'value' => 2
        ]);

        $pack6Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package6->id,
            'name' => 'Name1',
            'condition' => 'not equal',
            'value' => 2
        ]);

        $res = $this->json('GET', '/users/packages/' . $user1->id)
            ->seeJsonEquals([
                'data' => [
                    [
                        'id' => 3,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package3',
                            'information' => 'Information3',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 5,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package5',
                            'information' => 'Information5',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 6,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package6',
                            'information' => 'Information6',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 7,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package7',
                            'information' => 'Information7',
                            'companyId' => '1'
                        ]
                    ]
                ]
            ]);

        $res = $this->json('GET', '/users/packages/' . $user2->id)
            ->seeJsonEquals([
                'data' => [
                    [
                        'id' => 1,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package1',
                            'information' => 'Information1',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 4,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package4',
                            'information' => 'Information4',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 5,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package5',
                            'information' => 'Information5',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 7,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package7',
                            'information' => 'Information7',
                            'companyId' => '1'
                        ]
                    ]
                ]
            ]);

        $res = $this->json('GET', '/users/packages/' . $user3->id)
            ->seeJsonEquals([
                'data' => [
                    [
                        'id' => 2,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package2',
                            'information' => 'Information2',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 4,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package4',
                            'information' => 'Information4',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 6,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package6',
                            'information' => 'Information6',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 7,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package7',
                            'information' => 'Information7',
                            'companyId' => '1'
                        ]
                    ]
                ]
            ]);
    }

    public function testUserPackagesSupervisor()
    {
        // COMPANY
        $company = factory(\WA\DataStore\Company\Company::class)->create();

        $user1 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id, 'isSupervisor' => 0]);

        $user2 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id, 'isSupervisor' => 1]);

        // PACKAGES
        $package1 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package1',
            'information' => 'Information1',
        ]);
        $package2 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package2',
            'information' => 'Information2',
        ]);
        $package3 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package3',
            'information' => 'Information3',
        ]);

        $package4 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package4',
            'information' => 'Information4',
        ]);
        $package5 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package5',
            'information' => 'Information5',
        ]);

        // CONDITIONS
        $pack1Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package1->id,
            'name' => 'Supervisor?',
            'condition' => 'equal',
            'value' => 'Yes'
        ]); // OK!

        $pack2Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package2->id,
            'name' => 'Supervisor?',
            'condition' => 'not equal',
            'value' => 'Yes'
        ]); // OK!

        $pack3Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package3->id,
            'name' => 'Supervisor?',
            'condition' => 'equal',
            'value' => 'No'
        ]); // OK!

        $pack4Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package4->id,
            'name' => 'Supervisor?',
            'condition' => 'not equal',
            'value' => 'No'
        ]); // NO!

        $res = $this->json('GET', '/users/packages/' . $user1->id)
            ->seeJsonEquals([
                'data' => [
                    [
                        'id' => 2,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package2',
                            'information' => 'Information2',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 3,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package3',
                            'information' => 'Information3',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 5,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package5',
                            'information' => 'Information5',
                            'companyId' => '1'
                        ]
                    ]
                ]
            ]);

        $res = $this->json('GET', '/users/packages/' . $user2->id)
            ->seeJsonEquals([
                'data' => [
                    [
                        'id' => 1,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package1',
                            'information' => 'Information1',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 4,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package4',
                            'information' => 'Information4',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 5,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package5',
                            'information' => 'Information5',
                            'companyId' => '1'
                        ]
                    ]
                ]
            ]);
    }

    public function testUserPackagesAddress()
    {
        // COMPANY
        $company = factory(\WA\DataStore\Company\Company::class)->create();

        $user1 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user2 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user3 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);
        $user4 = factory(\WA\DataStore\User\User::class)->create(['companyId' => $company->id]);

        $address1 = factory(\WA\DataStore\Address\Address::class)->create([
            'country' => 'Catalonia',
            'state' => 'Barcelona',
            'city' => 'Barcelona',
        ]);

        $address2 = factory(\WA\DataStore\Address\Address::class)->create([
            'country' => 'Catalonia',
            'state' => 'Barcelona',
            'city' => 'Rubi',
        ]);

        $address3 = factory(\WA\DataStore\Address\Address::class)->create([
            'country' => 'Catalonia',
            'state' => 'Tarragona',
            'city' => 'Reus',
        ]);

        $address4 = factory(\WA\DataStore\Address\Address::class)->create([
            'country' => 'EEUU',
            'state' => 'Massachusetts',
            'city' => 'Boston',
        ]);

        $user1Address1 = factory(\WA\DataStore\User\UserAddress::class)->create([
            'userId' => $user1->id,
            'addressId' => $address1->id,
        ]);

        $user1Address2 = factory(\WA\DataStore\User\UserAddress::class)->create([
            'userId' => $user1->id,
            'addressId' => $address2->id,
        ]);

        $user2Address2 = factory(\WA\DataStore\User\UserAddress::class)->create([
            'userId' => $user2->id,
            'addressId' => $address2->id,
        ]);

        $user2Address3 = factory(\WA\DataStore\User\UserAddress::class)->create([
            'userId' => $user2->id,
            'addressId' => $address3->id,
        ]);

        $user3Address3 = factory(\WA\DataStore\User\UserAddress::class)->create([
            'userId' => $user3->id,
            'addressId' => $address3->id,
        ]);

        $user3Address4 = factory(\WA\DataStore\User\UserAddress::class)->create([
            'userId' => $user3->id,
            'addressId' => $address4->id,
        ]);

        $user4Address4 = factory(\WA\DataStore\User\UserAddress::class)->create([
            'userId' => $user4->id,
            'addressId' => $address4->id,
        ]);

        $user4Address1 = factory(\WA\DataStore\User\UserAddress::class)->create([
            'userId' => $user4->id,
            'addressId' => $address1->id,
        ]);

        // PACKAGES
        $package1 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package1',
            'information' => 'Information1'
        ]);
        $package2 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package2',
            'information' => 'Information2'
        ]);
        $package3 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package3',
            'information' => 'Information3'
        ]);
        $package4 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package4',
            'information' => 'Information4'
        ]);
        $package5 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package5',
            'information' => 'Information5'
        ]);
        $package6 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package6',
            'information' => 'Information6'
        ]);
        $package7 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package7',
            'information' => 'Information7'
        ]);
        $package8 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package8',
            'information' => 'Information8'
        ]);
        $package9 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package9',
            'information' => 'Information9'
        ]);

        $package10 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package10',
            'information' => 'Information10'
        ]);
        $package11 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package11',
            'information' => 'Information11'
        ]);
        $package12 = factory(\WA\DataStore\Package\Package::class)->create([
            'companyId' => $company->id,
            'name' => 'Package12',
            'information' => 'Information12'
        ]);

        // CONDITIONS
        $pack1Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package1->id,
            'name' => 'Country',
            'condition' => 'equal',
            'value' => 'Catalonia'
        ]);

        $pack1Condition2 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package1->id,
            'name' => 'State',
            'condition' => 'equal',
            'value' => 'Barcelona'
        ]);

        $pack1Condition3 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package1->id,
            'name' => 'City',
            'condition' => 'equal',
            'value' => 'Barcelona'
        ]);

        $pack2Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package2->id,
            'name' => 'Country',
            'condition' => 'contains',
            'value' => 'Cat'
        ]);

        $pack2Condition2 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package2->id,
            'name' => 'State',
            'condition' => 'contains',
            'value' => 'ona'
        ]);

        $pack2Condition3 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package2->id,
            'name' => 'City',
            'condition' => 'contains',
            'value' => 'e'
        ]);

        $pack3Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package3->id,
            'name' => 'Country',
            'condition' => 'not equal',
            'value' => 'Catalonia'
        ]);

        $pack3Condition2 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package3->id,
            'name' => 'State',
            'condition' => 'not equal',
            'value' => 'Barcelona'
        ]);

        $pack3Condition3 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package3->id,
            'name' => 'City',
            'condition' => 'not equal',
            'value' => 'Barcelona'
        ]);

        $pack4Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package4->id,
            'name' => 'Country',
            'condition' => 'equal',
            'value' => 'Catalonia'
        ]);

        $pack5Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package5->id,
            'name' => 'Country',
            'condition' => 'contains',
            'value' => 'alon'
        ]);

        $pack6Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package6->id,
            'name' => 'Country',
            'condition' => 'not equal',
            'value' => 'Catalonia'
        ]);

        $pack7Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package7->id,
            'name' => 'State',
            'condition' => 'equal',
            'value' => 'Barcelona'
        ]);

        $pack8Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package8->id,
            'name' => 'State',
            'condition' => 'contains',
            'value' => 'ona'
        ]);

        $pack9Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package9->id,
            'name' => 'State',
            'condition' => 'not equal',
            'value' => 'Barcelona'
        ]);

        $pack10Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package10->id,
            'name' => 'City',
            'condition' => 'equal',
            'value' => 'Barcelona'
        ]);

        $pack11Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package11->id,
            'name' => 'City',
            'condition' => 'contains',
            'value' => 'on'
        ]);

        $pack12Condition1 = factory(\WA\DataStore\Condition\Condition::class)->create([
            'packageId' => $package12->id,
            'name' => 'City',
            'condition' => 'not equal',
            'value' => 'Barcelona'
        ]);

        $res = $this->json('GET', '/users/packages/' . $user1->id)
            ->seeJsonEquals([
                'data' => [
                    [
                        'id' => 1,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package1',
                            'information' => 'Information1',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 2,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package2',
                            'information' => 'Information2',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 4,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package4',
                            'information' => 'Information4',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 5,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package5',
                            'information' => 'Information5',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 7,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package7',
                            'information' => 'Information7',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 8,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package8',
                            'information' => 'Information8',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 10,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package10',
                            'information' => 'Information10',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 11,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package11',
                            'information' => 'Information11',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 12,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package12',
                            'information' => 'Information12',
                            'companyId' => '1'
                        ]
                    ]
                ]
            ]);

        $res = $this->json('GET', '/users/packages/' . $user2->id)
            ->seeJsonEquals([
                'data' => [
                    [
                        'id' => 2,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package2',
                            'information' => 'Information2',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 4,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package4',
                            'information' => 'Information4',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 5,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package5',
                            'information' => 'Information5',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 7,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package7',
                            'information' => 'Information7',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 8,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package8',
                            'information' => 'Information8',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 9,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package9',
                            'information' => 'Information9',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 12,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package12',
                            'information' => 'Information12',
                            'companyId' => '1'
                        ]
                    ]
                ]
            ]);

        $res = $this->json('GET', '/users/packages/' . $user3->id)
            ->seeJsonEquals([
                'data' => [
                    [
                        'id' => 2,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package2',
                            'information' => 'Information2',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 3,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package3',
                            'information' => 'Information3',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 4,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package4',
                            'information' => 'Information4',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 5,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package5',
                            'information' => 'Information5',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 6,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package6',
                            'information' => 'Information6',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 8,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package8',
                            'information' => 'Information8',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 9,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package9',
                            'information' => 'Information9',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 11,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package11',
                            'information' => 'Information11',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 12,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package12',
                            'information' => 'Information12',
                            'companyId' => '1'
                        ]
                    ]
                ]
            ]);

        $res = $this->json('GET', '/users/packages/' . $user4->id)
            ->seeJsonEquals([
                'data' => [
                    [
                        'id' => 1,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package1',
                            'information' => 'Information1',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 2,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package2',
                            'information' => 'Information2',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 3,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package3',
                            'information' => 'Information3',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 4,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package4',
                            'information' => 'Information4',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 5,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package5',
                            'information' => 'Information5',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 6,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package6',
                            'information' => 'Information6',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 7,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package7',
                            'information' => 'Information7',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 8,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package8',
                            'information' => 'Information8',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 9,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package9',
                            'information' => 'Information9',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 10,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package10',
                            'information' => 'Information10',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 11,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package11',
                            'information' => 'Information11',
                            'companyId' => '1'
                        ]
                    ],
                    [
                        'id' => 12,
                        'type' => 'packages',
                        'attributes' => [
                            'name' => 'Package12',
                            'information' => 'Information12',
                            'companyId' => '1'
                        ]
                    ]
                ]
            ]);
    }
}