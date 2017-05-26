<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class ContentsApiTest extends TestCase
{
    use DatabaseMigrations;

 /**
  * A basic functional test for contents.
  */
 public function testGetContents()
 {
     $contents = factory(\WA\DataStore\Content\Content::class)->create();

     $this->get('/contents')
           ->seeJsonStructure([
               'data' => [
                   0 => ['type', 'id',
                       'attributes' => [
                           'content', 'active', 'owner_type', 'owner_id',
                       ],
                       'links',
                   ],

               ],

           ]);
 }

    public function testGetContentById()
    {
        $contents = factory(\WA\DataStore\Content\Content::class)->create();

        $this->get('/contents/'.$contents->id)
            ->seeJson([
                'type' => 'contents',
                'content' => $contents->content,
                'active' => "$contents->active",
                'owner_type' => $contents->owner_type,
                'owner_id' => "$contents->owner_id",

            ]);
    }

    public function testCreateContents()
    {
        $this->post('/contents',
            [
                'content' => 'Test Content',
                'active' => 1,
                'owner_type' => 'company',
                'owner_id' => 9,
            ])
            ->seeJson([
                'type' => 'contents',
                'content' => 'Test Content',
                'active' => 1,
                'owner_type' => 'company',
                'owner_id' => 9,
            ]);
    }

    public function testUpdateContents()
    {
        $contents = factory(\WA\DataStore\Content\Content::class)->create();

        $this->PATCH('/contents/'.$contents->id, [
            'content' => 'Test Content',
            'active' => $contents->active,
            'owner_type' => $contents->owner_type,
            'owner_id' => $contents->owner_id,
        ])
            ->seeJson([
                'type' => 'contents',
                'content' => 'Test Content',
                'active' => $contents->active,
                'owner_type' => $contents->owner_type,
                'owner_id' => $contents->owner_id,
            ]);
    }

    public function testDeleteContents()
    {
        $contents = factory(\WA\DataStore\Content\Content::class)->create();
        //$this->delete('/contents/'. $contents->id);
        //$response = $this->call('GET', '/contents/'.$contents->id);
        $response = $this->call('DELETE', '/contents/'.$contents->id);
        $this->assertEquals(204, $response->status());
    }
}
