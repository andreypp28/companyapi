<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use WA\DataStore\Modification\Modification;

class ModificationsApiTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic functional test for modifications.
     */
    public function testGetModifications()
    {
        $modification = factory(\WA\DataStore\Modification\Modification::class)->create();

        $res = $this->json('GET', 'modifications');

        $res->seeJsonStructure([
                'data' => [
                    0 => ['type', 'id',
                        'attributes' => [
                            'modType',
                            'value',
                        ],
                        'links',
                    ],
                ],
            ]);
    }

    public function testGetModificationById()
    {
        $modification = factory(\WA\DataStore\Modification\Modification::class)->create();

        $res = $this->json('GET', 'modifications/'.$modification->id)
            ->seeJson([
                'type' => 'modifications',
                'modType' => $modification->modType,
                'value' => $modification->value,
            ]);
    }

    public function testCreateModification()
    {
        $this->post('/modifications',
            [
                'data' => [
                    'type' => 'modifications',
                    'attributes' => [
                        'modType' => 'Modification Type',
                        'value' => 'Modification Value',
                    ],
                ],
            ])
            ->seeJson([
                'type' => 'modifications',
                'modType' => 'Modification Type',
                'value' => 'Modification Value',

            ]);
    }

    public function testUpdateModification()
    {
        $modification = factory(\WA\DataStore\Modification\Modification::class)->create();

        $this->PATCH('/modifications/'.$modification->id,
            [
                'data' => [
                    'type' => 'modifications',
                    'attributes' => [
                        'modType' => 'Modification Type Edit',
                        'value' => 'Modification Value Edit',
                    ],
                ],
            ])
            ->seeJson([
                'type' => 'modifications',
                'modType' => 'Modification Type Edit',
                'value' => 'Modification Value Edit',

            ]);
    }

    public function testDeleteModificationIfExists()
    {

        // CREATE & DELETE
        $modification = factory(\WA\DataStore\Modification\Modification::class)->create();
        $responseDel = $this->call('DELETE', '/modifications/'.$modification->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', '/modifications/'.$modification->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeleteModificationIfNoExists()
    {

        // DELETE NO EXISTING.
        $responseDel = $this->call('DELETE', '/modifications/1');
        $this->assertEquals(404, $responseDel->status());
    }
}
