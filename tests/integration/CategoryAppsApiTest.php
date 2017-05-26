<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use WA\DataStore\Category\CategoryApps;
use WA\DataStore\Image\Image;

class CategoryAppsApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetCategoryApps()
    {
        factory(\WA\DataStore\Category\CategoryApp::class, 40)->create();

        $res = $this->json('GET', 'categoryapps')
            ->seeJsonStructure([
                'data' => [
                    0 => [
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

    public function testGetCategoryAppByIdIfExists()
    {
        $categoryApp = factory(\WA\DataStore\Category\CategoryApp::class)->create();

        $res = $this->json('GET', 'categoryapps/'.$categoryApp->id)
            ->seeJson([
                'type' => 'categoryapps',
                'name' => $categoryApp->name,
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

    public function testGetCategoryAppByIdIfNoExists()
    {
        $categoryAppId = factory(\WA\DataStore\Category\CategoryApp::class)->create()->id;
        $categoryAppId = $categoryAppId + 10;

        $response = $this->call('GET', 'categoryapps/'.$categoryAppId);
        $this->assertEquals(404, $response->status());
    }

    public function testGetCategoryAppByIdandIncludesApps()
    {
        $categoryApp = factory(\WA\DataStore\Category\CategoryApp::class)->create();

        $app1 = factory(\WA\DataStore\App\App::class)->create()->id;
        $app2 = factory(\WA\DataStore\App\App::class)->create()->id;

        $dataApps = array($app1, $app2);

        $categoryApp->apps()->sync($dataApps);

        $this->json('GET', 'categoryapps/'.$categoryApp->id.'?include=apps')
            ->seeJsonStructure([
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

    public function testGetCategoryAppByIdandIncludesImages()
    {
        $categoryApp = factory(\WA\DataStore\Category\CategoryApp::class)->create();

        $image1 = factory(\WA\DataStore\Image\Image::class)->create()->id;
        $image2 = factory(\WA\DataStore\Image\Image::class)->create()->id;

        $dataImages = array($image1, $image2);

        $categoryApp->images()->sync($dataImages);

        $this->json('GET', 'categoryapps/'.$categoryApp->id.'?include=images')
            ->seeJsonStructure([
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
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'originalName',
                            'filename',
                            'mimeType',
                            'extension',
                            'size',
                            'url',
                        ],
                        'links' => [
                            'self',
                        ],
                    ],

                ],
            ]);
    }

    public function testCreateCategoryApp()
    {
        $categoryApp = factory(\WA\DataStore\Category\CategoryApp::class)->create();

        $app1 = factory(\WA\DataStore\App\App::class)->create()->id;
        $app2 = factory(\WA\DataStore\App\App::class)->create()->id;

        $image1 = factory(\WA\DataStore\Image\Image::class)->create()->id;
        $image2 = factory(\WA\DataStore\Image\Image::class)->create()->id;

        $this->json('POST', 'categoryapps',
            [
                'data' => [
                    'type' => 'categoryapps',
                    'attributes' => [
                        'name' => 'nameCategoryApp',
                    ],
                    'relationships' => [
                        'apps' => [
                            'data' => [
                                ['type' => 'apps', 'id' => $app1],
                                ['type' => 'apps', 'id' => $app2],
                            ],
                        ],
                        'images' => [
                            'data' => [
                                ['type' => 'images', 'id' => $image1],
                                ['type' => 'images', 'id' => $image2],
                            ],
                        ],
                    ],
                ],
            ]
            )->seeJson(
            [
                'type' => 'categoryapps',
                'name' => 'nameCategoryApp',
            ]);
    }

    public function testCreateCategoryAppReturnNoValidData()
    {
        // 'data' no valid.
        $this->json('POST', 'categoryapps',
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

    public function testCreateCategoryAppReturnNoValidType()
    {
        // 'type' no valid.
        $this->json('POST', 'categoryapps',
            [
                'data' => [
                    'NoValid' => 'categoryapps',
                    'attributes' => [
                        'name' => 'nameCategoryApp',
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

    public function testCreateCategoryAppReturnNoValidAttributes()
    {
        // 'attributes' no valid.
        $this->json('POST', 'categoryapps',
            [
                'data' => [
                    'type' => 'categoryapps',
                    'NoValid' => [
                        'name' => 'nameCategoryApp',
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

    public function testCreateCategoryAppReturnRelationshipNoExists()
    {
        $this->json('POST', 'categoryapps',
        [
            'data' => [
                'type' => 'categoryapps',
                'attributes' => [
                    'name' => 'nameCategoryApp',
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
            'type' => 'categoryapps',
            'name' => 'nameCategoryApp',
        ]);
    }

    public function testCreateCategoryAppReturnRelationshipNoExistsData()
    {
        $this->json('POST', 'categoryapps',
        [
            'data' => [
                'type' => 'categoryapps',
                'attributes' => [
                    'name' => 'nameCategoryApp',
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
            'type' => 'categoryapps',
            'name' => 'nameCategoryApp',
        ]);
    }

    public function testCreateCategoryAppReturnRelationshipNoAppsType()
    {
        $this->json('POST', 'categoryapps',
        [
            'data' => [
                'type' => 'categoryapps',
                'attributes' => [
                    'name' => 'nameCategoryApp',
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
            'type' => 'categoryapps',
            'name' => 'nameCategoryApp',
        ]);
    }

    public function testCreateCategoryAppReturnRelationshipNoIdExists()
    {
        $this->json('POST', 'categoryapps',
        [
            'data' => [
                'type' => 'categoryapps',
                'attributes' => [
                    'name' => 'nameCategoryApp',
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
            'type' => 'categoryapps',
            'name' => 'nameCategoryApp',
        ]);
    }

    public function testUpdateCategoryApp()
    {
        $categoryApp = factory(\WA\DataStore\Category\CategoryApp::class)->create(
            ['name' => 'nameCategoryApp1']
        );
        $categoryAppAux = factory(\WA\DataStore\Category\CategoryApp::class)->create(
            ['name' => 'nameCategoryApp2']
        );

        $this->assertNotEquals($categoryApp->id, $categoryAppAux->id);
        $this->assertNotEquals($categoryApp->name, $categoryAppAux->name);

        $this->json('PATCH', 'categoryapps/'.$categoryApp->id,
            [
                'data' => [
                    'type' => 'categoryapps',
                    'attributes' => [
                        'name' => $categoryAppAux->name,
                    ],
                ],
            ])
            ->seeJson(
            [
                'type' => 'categoryapps',
                'name' => $categoryAppAux->name,
            ]);
    }

    public function testDeleteCategoryAppIfExists()
    {
        // CREATE & DELETE
        $categoryApp = factory(\WA\DataStore\Category\CategoryApp::class)->create();
        $responseDel = $this->call('DELETE', 'categoryapps/'.$categoryApp->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', 'categoryapps/'.$categoryApp->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeleteCategoryAppIfNoExists()
    {
        // DELETE NO EXISTING.
        $responseDel = $this->call('DELETE', 'categoryapps/1');
        $this->assertEquals(404, $responseDel->status());
    }
}
