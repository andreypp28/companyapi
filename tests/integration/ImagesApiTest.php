<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class ImagesApiTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic functional test for Apps.
     */
    public function testGetImages()
    {
        factory(\WA\DataStore\Image\Image::class, 40)->create();

        $this->json('GET', 'images')
            ->seeJsonStructure([
                'data' => [
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

    public function testGetImageById()
    {
        if(!is_dir('./storage/app/public')){
            mkdir('./storage/app/public', 0755, true);
        }
        copy('./database/seeds/imagesseeder/phpFmndT1.png', './storage/app/public/filename.png');

        $image = factory(\WA\DataStore\Image\Image::class)->create([
                'originalName' => 'iphone6.png',
                'filename' => 'filename',
                'mimeType' => 'image/png',
                'extension' => 'png',
                'size' => 235417,
                'url' => 'filename.png',
            ]);

        $res = $this->json('GET', 'images/'.$image->id);

        $this->assertEquals('image/png', $res->response->headers->get('Content-Type'));

        $this->call('DELETE', 'images/'.$image->id);
    }

    public function testGetImageInformationById()
    {
        $image = factory(\WA\DataStore\Image\Image::class)->create();

        $this->json('GET', 'images/info/'.$image->id)
            ->seeJson([
                'type' => 'images',
                'originalName' => $image->originalName,
                'filename' => $image->filename,
                'mimeType' => $image->mimeType,
                'extension' => $image->extension,
                'size' => $image->size,
                'url' => $image->url,
            ]);
    }

    public function testCreateImage()
    {
        if(!is_dir('./storage/app/public')){
            mkdir('./storage/app/public', 0755, true);
        }
        copy('./database/seeds/imagesseeder/phpFmndT1.png', './storage/app/public/filename.png');

        $image = factory(\WA\DataStore\Image\Image::class)->create([
                'originalName' => 'iphone6.png',
                'filename' => 'filename',
                'mimeType' => 'image/png',
                'extension' => 'png',
                'size' => 235417,
                'url' => 'filename.png',
            ]);

        $uploadedFile = new Symfony\Component\HttpFoundation\File\UploadedFile(
            './storage/app/public/filename.png',
            'iphone6.png',
            'image/png',
            235417,
            null,
            true
        );

        $response = $this->call(
                'POST',
                'images',
                [],
                [],
                ['filename' => $uploadedFile]
            );

        $this->assertStringStartsWith('{"data":{"type":"images","id":"2","attributes"',
            $response->getContent()
            );

        $this->call('DELETE', 'images/'.$image->id);
    }

    public function testDeleteImageIfExists()
    {
        $image = factory(\WA\DataStore\Image\Image::class)->create();
        $responseDel = $this->call('DELETE', 'images/'.$image->id);
        $this->assertEquals(200, $responseDel->status());
        $responseGet = $this->call('GET', 'images/'.$image->id);
        $this->assertEquals(404, $responseGet->status());
    }

    public function testDeleteImageIfNoExists()
    {
        $responseDel = $this->call('DELETE', 'images/1');
        $this->assertEquals(404, $responseDel->status());
    }
}
