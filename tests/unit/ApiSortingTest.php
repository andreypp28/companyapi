<?php

class ApiSortingTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations;

    public function testCanIncludeSortInMeta()
    {

        $this->json('GET', '/devices?sort=-identification')
            ->seeJson(['sort' => '-identification']);
    }

    public function testCanSortResource()
    {
        factory(\WA\DataStore\Device\Device::class, 10)->create();

        $response = $this->call('GET', '/devices?sort=identification');
        $this->assertEquals('application/json', $response->headers->get('Content-type'));
        $json = json_decode($response->getContent());

        $sorted = [];
        foreach ($json->data as $row) {
            $sorted[] = $row->attributes->identification;
        }
        $resorted = $sorted;
        sort($resorted);
        $this->assertEquals($resorted, $sorted);
    }

    public function testCanInvertSortResource()
    {
        factory(\WA\DataStore\Device\Device::class, 10)->create();

        $response = $this->call('GET', '/devices?sort=-identification');
        $json = json_decode($response->getContent());
        $sorted = [];
        foreach ($json->data as $row) {
            $sorted[] = $row->attributes->identification;
        }
        $resorted = $sorted;
        sort($resorted);

        // Now the first element of the resorted array should match the last element of the result array
        $this->assertEquals($resorted[0], end($sorted));
    }

    // Per JSONAPI, invalid criteria MUST return a 400
    public function testWorkProperlyWithIncorrectSortCriteria()
    {
        $response = $this->call('GET', '/devices?sort=-blahblah');
        $this->assertEquals(400, $response->status());
    }
}
