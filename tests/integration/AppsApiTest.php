<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class AppsApiTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic functional test for Apps.
     */
    public function testGetApps()
    {
        factory(\WA\DataStore\App\App::class, 40)->create();

        $this->json('GET', 'apps')
            ->seeJsonStructure([
                'data' => [
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'type',
                            'image',
                            'description',
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

    public function testGetAppById()
    {
        $app = factory(\WA\DataStore\App\App::class)->create();

        $res = $this->json('GET', 'apps/'.$app->id)
            ->seeJson([
                'type' => 'apps',
                'type' => $app->type,
                'image' => $app->image,
                'description' => $app->description,
            ]);
    }

    public function testCreateApp()
    {
        $this->json('POST', 'apps',
            [
                'data' => [
                    'type' => 'apps',
                    'attributes' => [
                        'type' => 'AppType',
                        'image' => 'AppImageLink',
                        'description' => 'AppDescription',
                    ],
                ],
            ])
            ->seeJson([
                'type' => 'apps',
                'type' => 'AppType',
                'image' => 'AppImageLink',
                'description' => 'AppDescription',
            ]);
    }

    public function testUpdateApp()
    {
        $app1 = factory(\WA\DataStore\App\App::class)->create();
        $app2 = factory(\WA\DataStore\App\App::class)->create();

        $this->assertNotEquals($app1->id, $app2->id);
        $this->assertNotEquals($app1->type, $app2->type);
        $this->assertNotEquals($app1->image, $app2->image);
        $this->assertNotEquals($app1->description, $app2->description);

        $this->assertNotEquals($app1->id, $app2->id);

        $this->json('GET', 'apps/'.$app1->id)
            ->seeJson([
                'type' => 'apps',
                'type' => $app1->type,
                'image' => $app1->image,
                'description' => $app1->description,
            ]);

        $this->json('PATCH', 'apps/'.$app1->id,
            [
                'data' => [
                    'type' => 'apps',
                    'attributes' => [
                        'type' => $app2->type,
                        'image' => $app2->image,
                        'description' => $app2->description,
                    ],
                ],
            ])
            ->seeJson([
                'type' => 'apps',
                'id' => $app1->id,
                'type' => $app2->type,
                'image' => $app2->image,
                'description' => $app2->description,
            ]);
    }

    public function testDeleteAppIfExists()
    {
        $app = factory(\WA\DataStore\App\App::class)->create();
        $responseDel = $this->call('DELETE', 'apps/'.$app->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', 'apps/'.$app->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeleteAppIfNoExists()
    {
        $responseDel = $this->call('DELETE', 'apps/1');
        $this->assertEquals(404, $responseDel->status());
    }
}
