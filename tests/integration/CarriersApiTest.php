<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class CarriersApiTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic functional test for Carriers.
     */
    public function testGetCarriers()
    {
        $carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();

        $this->json('GET', 'carriers')
            ->seeJsonStructure([
                'data' => [
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'presentation',
                            'active',
                            'locationId',
                            'shortName',
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
                ],
            ]);
    }

    public function testGetCarrierById()
    {
        $carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();

        $res = $this->json('GET', 'carriers/'.$carrier->id)
            ->seeJson([
                'type' => 'carriers',
                'name' => $carrier->name,
                'presentation' => $carrier->presentation,
                'active' => "$carrier->active",
                'locationId' => "$carrier->locationId",
                'shortName' => $carrier->shortName,
            ]);
    }

    public function testCreateCarrier()
    {
        $locationId = factory(WA\DataStore\Location\Location::class)->create()->id;

        $this->json('POST', 'carriers',
            [
                'data' => [
                    'type' => 'carriers',
                    'attributes' => [
                        'name' => 'Carrier Name',
                        'presentation' => 'Carrier Presentation',
                        'active' => 1,
                        'locationId' => $locationId,
                        'shortName' => 'Carrier ShortName',
                    ],
                ],
            ])
            ->seeJson([
                'type' => 'carriers',
                'name' => 'Carrier Name',
                'presentation' => 'Carrier Presentation',
                'active' => 1,
                'locationId' => $locationId,
                'shortName' => 'Carrier ShortName',

            ]);
    }

    public function testUpdateCarrier()
    {
        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        $carrier2 = factory(\WA\DataStore\Carrier\Carrier::class)->create();

        $this->assertNotEquals($carrier1->id, $carrier2->id);
        $this->assertNotEquals($carrier1->locationId, $carrier2->locationId);

        $this->assertNotEquals($carrier1->id, $carrier2->id);

        $this->json('GET', 'carriers/'.$carrier1->id)
            ->seeJson([
                'type' => 'carriers',
                'name' => $carrier1->name,
                'presentation' => $carrier1->presentation,
                'active' => "$carrier1->active",
                'locationId' => "$carrier1->locationId",
                'shortName' => $carrier1->shortName,
            ]);

        $res = $this->json('PATCH', 'carriers/'.$carrier1->id,
            [
                'data' => [
                    'type' => 'carriers',
                    'attributes' => [
                        'name' => $carrier2->name,
                        'presentation' => $carrier2->presentation,
                        'active' => $carrier2->active,
                        'locationId' => $carrier1->locationId,
                        'shortName' => $carrier1->shortName,
                    ],
                ],
            ])
            //Log::debug("Users: ".print_r($res->response->getContent(), true));
            ->seeJson([
                //'type' => 'carriers',
                'id' => $carrier1->id,
                'name' => $carrier2->name,
                'presentation' => $carrier2->presentation,
                'active' => $carrier2->active,
                'locationId' => $carrier1->locationId,
                'shortName' => $carrier1->shortName,
            ]);
    }

    public function testDeleteCarrierIfExists()
    {
        $carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        $responseDel = $this->call('DELETE', '/carriers/'.$carrier->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', '/carriers/'.$carrier->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeleteCarrierIfNoExists()
    {
        $responseDel = $this->call('DELETE', '/carriers/1');
        $this->assertEquals(404, $responseDel->status());
    }
}
