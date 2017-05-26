<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use WA\DataStore\Image\Image;

class PresetsApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetPresets()
    {
        factory(\WA\DataStore\Preset\Preset::class, 40)->create();

        $res = $this->json('GET', 'presets');

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

    public function testGetPresetByIdIfExists()
    {
        $preset = factory(\WA\DataStore\Preset\Preset::class)->create();

        $res = $this->json('GET', 'presets/'.$preset->id)
            ->seeJson([
                'type' => 'presets',
                'name' => $preset->name,
            ]);

        $res->seeJsonStructure([
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
            ],
        ]);
    }

    public function testGetPresetByIdIfNoExists()
    {
        $presetId = factory(\WA\DataStore\Preset\Preset::class)->create()->id;
        $presetId = $presetId + 10;

        $response = $this->call('GET', 'presets/'.$presetId);
        $this->assertEquals(404, $response->status());
    }

    public function testGetPresetByIdandIncludesDeviceVariations()
    {
        $preset = factory(\WA\DataStore\Preset\Preset::class)->create();

        $device = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;

        $preset->deviceVariations()->sync(array($device));

        $this->json('GET', 'presets/'.$preset->id.'?include=devicevariations')
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
                        ],
                    ],
                ],
            ]);
    }

    /*public function testGetPresetByIdandIncludesImages()
    {
        $preset = factory(\WA\DataStore\Preset\Preset::class)->create();

        $image1 = factory(\WA\DataStore\Image\Image::class)->create()->id;
        $image2 = factory(\WA\DataStore\Image\Image::class)->create()->id;

        $dataImages = array($image1, $image2);

        $preset->images()->sync($dataImages);

        $this->json('GET', 'presets/'.$preset->id.'?include=images')
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
    }*/

    public function testCreatePreset()
    {
        $preset = factory(\WA\DataStore\Preset\Preset::class)->create();

        $device1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;
        $device2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create()->id;
        $company = factory(\WA\DataStore\Company\Company::class)->create()->id;

        $this->json('POST', 'presets',
            [
                'data' => [
                    'type' => 'presets',
                    'attributes' => [
                        'name' => 'namePreset',
                        'companyId' => $company,
                    ],
                    'relationships' => [
                        'devicevariations' => [
                            'data' => [
                                ['type' => 'devicevariations', 'id' => $device1],
                                ['type' => 'devicevariations', 'id' => $device2],
                            ],
                        ],
                        'companies' => [
                            'data' => [
                                ['type' => 'companies', 'id' => $company],
                            ],
                        ],
                    ],
                ],
            ]
            )->seeJson(
            [
                'type' => 'presets',
                'name' => 'namePreset',
                'companyId' => null,
            ]);
    }

    public function testCreatePresetReturnNoValidData()
    {
        // 'data' no valid.
        $this->json('POST', 'presets',
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

    public function testCreatePresetReturnNoValidType()
    {
        // 'type' no valid.
        $this->json('POST', 'presets',
            [
                'data' => [
                    'NoValid' => 'presets',
                    'attributes' => [
                        'name' => 'namePreset',
                        'companyId' => 1,
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

    public function testCreatePresetReturnNoValidAttributes()
    {
        // 'attributes' no valid.
        $this->json('POST', 'presets',
            [
                'data' => [
                    'type' => 'presets',
                    'NoValid' => [
                        'name' => 'namePreset',
                        'companyId' => 1,
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

    public function testCreatePresetReturnRelationshipNoExists()
    {
        $this->json('POST', 'presets',
        [
            'data' => [
                'type' => 'presets',
                'attributes' => [
                    'name' => 'namePreset',
                    'companyId' => 1,
                ],
                'relationships' => [
                    'IgnoreType' => [
                        'data' => [
                            ['type' => 'devicevariations', 'id' => '1'],
                            ['type' => 'devicevariations', 'id' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'presets',
            'name' => 'namePreset',
            'companyId' => null,
        ]);
    }

    public function testCreatePresetReturnRelationshipNoExistsData()
    {
        $this->json('POST', 'presets',
        [
            'data' => [
                'type' => 'presets',
                'attributes' => [
                    'name' => 'namePreset',
                    'companyId' => 1,
                ],
                'relationships' => [
                    'devicevariations' => [
                        'IgnoreData' => [
                            ['type' => 'devicevariations', 'id' => '1'],
                            ['type' => 'devicevariations', 'id' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'presets',
            'name' => 'namePreset',
            'companyId' => null,
        ]);
    }

    public function testCreatePresetReturnRelationshipNoAppsType()
    {
        $this->json('POST', 'presets',
        [
            'data' => [
                'type' => 'presets',
                'attributes' => [
                    'name' => 'namePreset',
                    'companyId' => 1,
                ],
                'relationships' => [
                    'apps' => [
                        'data' => [
                            ['type' => 'NoDevices', 'id' => '1'],
                            ['type' => 'NoDevices', 'id' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'presets',
            'name' => 'namePreset',
            'companyId' => null,
        ]);
    }

    public function testCreatePresetReturnRelationshipNoIdExists()
    {
        $this->json('POST', 'presets',
        [
            'data' => [
                'type' => 'presets',
                'attributes' => [
                    'name' => 'namePreset',
                    'companyId' => 1,
                ],
                'relationships' => [
                    'devicevariations' => [
                        'data' => [
                            ['type' => 'devicevariations', 'aa' => '1'],
                            ['type' => 'devicevariations', 'aa' => '2'],
                        ],
                    ],
                ],
            ],
        ]
        )->seeJson(
        [
            'type' => 'presets',
            'name' => 'namePreset',
            'companyId' => null,
        ]);
    }

    public function testUpdatePreset()
    {
        $preset = factory(\WA\DataStore\Preset\Preset::class)->create(
            ['name' => 'namePreset1']
        );
        $presetAux = factory(\WA\DataStore\Preset\Preset::class)->create(
            ['name' => 'namePreset2']
        );

        $this->assertNotEquals($preset->id, $presetAux->id);
        $this->assertNotEquals($preset->name, $presetAux->name);

        $this->json('PATCH', 'presets/'.$preset->id,
            [
                'data' => [
                    'type' => 'presets',
                    'attributes' => [
                        'name' => $presetAux->name,
                    ],
                ],
            ])
            ->seeJson(
            [
                'type' => 'presets',
                'name' => $presetAux->name,
            ]);
    }

    public function testDeletePresetIfExists()
    {
        // CREATE & DELETE
        $preset = factory(\WA\DataStore\Preset\Preset::class)->create();
        $responseDel = $this->call('DELETE', 'presets/'.$preset->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', 'presets/'.$preset->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeletePresetIfNoExists()
    {
        // DELETE NO EXISTING.
        $responseDel = $this->call('DELETE', 'presets/1');
        $this->assertEquals(404, $responseDel->status());
    }
}
