<?php

class ApiFiltersTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations;

    public function testCanIncludeFiltersInMeta()
    {
        $this->json('GET', '/devices?filter[identification]=296')
            ->seeJson(['filter' => ['[identification]=296']]);
    }

    public function testCanIncludeMultipleFiltersInMeta()
    {
        $this->json('GET', '/devices?filter[identification]=296&filter[id]=15')
            ->seeJson(['filter' => ['[identification]=296', '[id]=15']]);
    }

    public function testCanIncludeFiltersWithDelimittedCriteriaInMeta()
    {

        $this->json('GET', '/devices?filter[id]=2,4')
            ->seeJson(['filter' => ['[id]=2,4']]);
    }

    // Per JSONAPI, invalid criteria MUST return a 400
    public function testWorkProperlyWithIncorrectSortCriteria()
    {
        $response = $this->call('GET', '/devices?filter[blahblah]=9001');
        $this->assertEquals(400, $response->status());
    }

    public function testCanIncludeFiltersWithOrCriteriaInMeta()
    {
        $this->json('GET', '/devices?include=devicetypes&filter[]=[devicetypes.name]=Smartphone[or][devicetypes.name]=Tablet')
            ->seeJson(['filter' => ['[0]=[devicetypes.name]=Smartphone[or][devicetypes.name]=Tablet']]);
    }

    public function testCanIncludeFiltersWithMultipleOrCriteriaInMeta()
    {
        $this->json('GET', '/devices?include=devicetypes&filter[]=[devicetypes.name]=Smartphone[or][devicetypes.name]=Tablet&filter[]=[devicetypes.name]=Smartphone[or][devicetypes.name]=Tablet')
            ->seeJson(['filter' => ['[0]=[devicetypes.name]=Smartphone[or][devicetypes.name]=Tablet', '[1]=[devicetypes.name]=Smartphone[or][devicetypes.name]=Tablet']]);
    }

    public function testFilterModelByItsOwnAttributesStringDefault() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name1']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name122']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name]=name1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesStringDefault: " . print_r($resArray, true));
        $this->assertCount(1, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['meta']->filter, ['[name]=name1']);
    }

    public function testFilterModelByItsOwnAttributesStringEqual() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name1']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name122']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][eq]=name1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesStringEqual: " . print_r($resArray, true));
        $this->assertCount(1, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['meta']->filter, ['[name]=name1']);
    }

    public function testFilterModelByItsOwnAttributesStringLike() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name1']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name122']);
        $device4 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name2']);
        $device5 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name5']);
        $device6 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Niame16']);
        $device7 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'niame167']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][like]=*name1*')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesStringLike: " . print_r($resArray, true));
        $this->assertCount(3, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device2->id);
        $this->assertEquals($resArray['data'][2]->id, $device3->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][like]=*name1*']);
    }

    public function testFilterModelByItsOwnAttributesStringNotEqual() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name1']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name122']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][ne]=name1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesStringNotEqual: " . print_r($resArray, true));
        $this->assertCount(2, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device2->id);
        $this->assertEquals($resArray['data'][1]->id, $device3->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][ne]=name1']);
    }

    public function testFilterModelByItsOwnAttributesStringDefaultMultiple() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name1']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name122']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name]=name1,name122')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesStringDefaultMultiple: " . print_r($resArray, true));
        $this->assertCount(2, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device3->id);
        $this->assertEquals($resArray['meta']->filter, ['[name]=name1,name122']);
    }

    public function testFilterModelByItsOwnAttributesStringEqualMultiple() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name1']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name122']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][eq]=name1,name122')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesStringEqualMultiple: " . print_r($resArray, true));
        $this->assertCount(2, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device3->id);
        $this->assertEquals($resArray['meta']->filter, ['[name]=name1,name122']);
    }

    public function testFilterModelByItsOwnAttributesStringLikeMultiple() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name1']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name122']);
        $device4 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name2']);
        $device5 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name5']);
        $device6 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Niame16']);
        $device7 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'niame167']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][like]=*name1*,*nia*')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesStringLikeMultiple: " . print_r($resArray, true));
        $this->assertCount(5, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device2->id);
        $this->assertEquals($resArray['data'][2]->id, $device3->id);
        $this->assertEquals($resArray['data'][3]->id, $device6->id);
        $this->assertEquals($resArray['data'][4]->id, $device7->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][like]=*name1*,*nia*']);
    }

    public function testFilterModelByItsOwnAttributesStringLikeMultipleOR() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name1']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name122']);
        $device4 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name2']);
        $device5 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name5']);
        $device6 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Niame16']);
        $device7 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'niame167']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[]=[name][like]=*name1*[or][name][like]=*nia*')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesStringLikeMultipleOR: " . print_r($resArray, true));
        $this->assertCount(5, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device2->id);
        $this->assertEquals($resArray['data'][2]->id, $device3->id);
        $this->assertEquals($resArray['data'][3]->id, $device6->id);
        $this->assertEquals($resArray['data'][4]->id, $device7->id);
        $this->assertEquals($resArray['meta']->filter, ['[0]=[name][like]=*name1*[or][name][like]=*nia*']);
    }

    public function testFilterModelByItsOwnAttributesStringNotEqualMultiple() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'Name1']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'name122']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][ne]=name1,Name1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesStringNotEqualMultiple: " . print_r($resArray, true));
        $this->assertCount(1, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device3->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][ne]=name1,Name1']);
    }

    public function testFilterModelByItsOwnAttributesNumberDefault() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name]=1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberDefault: " . print_r($resArray, true));
        $this->assertCount(1, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['meta']->filter, ['[name]=1']);
    }

    public function testFilterModelByItsOwnAttributesNumberEqual() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][eq]=1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberEqual: " . print_r($resArray, true));
        $this->assertCount(1, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['meta']->filter, ['[name]=1']);
    }

    public function testFilterModelByItsOwnAttributesNumberLike() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '12']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][like]=*1*')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberLike: " . print_r($resArray, true));
        $this->assertCount(2, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device2->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][like]=*1*']);
    }

    public function testFilterModelByItsOwnAttributesNumberNotEqual() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][ne]=1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberNotEqual: " . print_r($resArray, true));
        $this->assertCount(2, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device2->id);
        $this->assertEquals($resArray['data'][1]->id, $device3->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][ne]=1']);
    }

//'gt', 'lt', 'ge', 'gte','lte','le','ne','eq','like':

    public function testFilterModelByItsOwnAttributesNumberGreaterThan() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][gt]=1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberGreaterThan: " . print_r($resArray, true));
        $this->assertCount(2, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device2->id);
        $this->assertEquals($resArray['data'][1]->id, $device3->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][gt]=1']);
    }

    public function testFilterModelByItsOwnAttributesNumberLessThan() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][lt]=1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberLessThan: " . print_r($resArray, true));
        $this->assertCount(0, $resArray['data']);
        $this->assertEquals($resArray['meta']->filter, ['[name][lt]=1']);
    }

    public function testFilterModelByItsOwnAttributesNumberGreaterThanEqual() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][gte]=1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberGreaterThanEqual: " . print_r($resArray, true));
        $this->assertCount(3, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device2->id);
        $this->assertEquals($resArray['data'][2]->id, $device3->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][gte]=1']);
    }

    public function testFilterModelByItsOwnAttributesNumberGreaterThanEqual2() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][ge]=1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberGreaterThanEqual2: " . print_r($resArray, true));
        $this->assertCount(3, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device2->id);
        $this->assertEquals($resArray['data'][2]->id, $device3->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][ge]=1']);
    }

    public function testFilterModelByItsOwnAttributesNumberLessThanEqual() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][lte]=1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberLessThanEqual: " . print_r($resArray, true));
        $this->assertCount(1, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][lte]=1']);
    }

    public function testFilterModelByItsOwnAttributesNumberLessThanEqual2() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[name][le]=1')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberLessThanEqual2: " . print_r($resArray, true));
        $this->assertCount(1, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['meta']->filter, ['[name][le]=1']);
    }

    public function testFilterModelByItsOwnAttributesNumberMultipleLEGE() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[]=[name][le]=1[or][name][ge]=3')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberMultipleLEGE: " . print_r($resArray, true));
        $this->assertCount(2, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device3->id);
        $this->assertEquals($resArray['meta']->filter, ['[0]=[name][le]=1[or][name][ge]=3']);
    }

    public function testFilterModelByItsOwnAttributesNumberMultipleLTGT() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => '3']);

        $resArray = (array)json_decode($this->json('GET', '/devices?filter[]=[name][lt]=1[or][name][gt]=3')->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsOwnAttributesNumberMultipleLTGT: " . print_r($resArray, true));
        $this->assertCount(0, $resArray['data']);
        $this->assertEquals($resArray['meta']->filter, ['[0]=[name][lt]=1[or][name][gt]=3']);
    }

    public function testFilterModelByItsIncludes() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device3']);

        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier1']);
        $carrier2 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier2']);

        $devvar1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device1->id,
            'priceRetail' => 100
        ]);
        $devvar2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device1->id,
            'priceRetail' => 200
        ]);
        $devvar3 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device2->id,
            'priceRetail' => 300
        ]);
        $devvar4 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device2->id,
            'priceRetail' => 400
        ]);
        $devvar5 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier2->id,
            'deviceId' => $device2->id,
            'priceRetail' => 500
        ]);
        $devvar6 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier2->id,
            'deviceId' => $device2->id,
            'priceRetail' => 600
        ]);
        $devvar7 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier2->id,
            'deviceId' => $device3->id,
            'priceRetail' => 700
        ]);
        $devvar8 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier2->id,
            'deviceId' => $device3->id,
            'priceRetail' => 800
        ]);

        $resArray = (array)json_decode($this->json('GET', '/devices?include=devicevariations,devicevariations.carriers&filter[devicevariations.carrierId]=' . $carrier1->id)->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsIncludes: " . print_r($resArray, true));
        $this->assertCount(2, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device2->id);
        $this->assertEquals($resArray['meta']->filter, ['[devicevariations.carrierId]=1']);
        $this->assertCount(8, $resArray['included']);
    }

    public function testFilterModelByItsIncludesOR() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device3']);
        $device4 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device4']);
        $device5 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device5']);

        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier1']);
        $carrier2 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier2']);
        $carrier3 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier3']);

        $devvar1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device1->id,
            'priceRetail' => 100
        ]);
        $devvar2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device2->id,
            'priceRetail' => 200
        ]);
        $devvar3 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier2->id,
            'deviceId' => $device3->id,
            'priceRetail' => 300
        ]);
        $devvar4 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier2->id,
            'deviceId' => $device4->id,
            'priceRetail' => 400
        ]);
        $devvar5 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier3->id,
            'deviceId' => $device4->id,
            'priceRetail' => 400
        ]);
        $devvar6 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier3->id,
            'deviceId' => $device5->id,
            'priceRetail' => 500
        ]);

        $resArray = (array)json_decode($this->json('GET', '/devices?include=devicevariations,devicevariations.carriers&filter[]=[devicevariations.carrierId]=' . $carrier1->id . '[or][devicevariations.carrierId]=' . $carrier3->id)->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsIncludesOR: " . print_r($resArray, true));
        $this->assertCount(4, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device2->id);
        $this->assertEquals($resArray['data'][2]->id, $device4->id);
        $this->assertEquals($resArray['data'][3]->id, $device5->id);
        $this->assertEquals($resArray['meta']->filter, ['[0]=[devicevariations.carrierId]=1[or][devicevariations.carrierId]=3']);
        $this->assertCount(8, $resArray['included']);
    }

    public function testFilterModelByItsIncludesORMoreThanTwo() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device3']);
        $device4 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device4']);
        $device5 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device5']);

        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier1']);
        $carrier2 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier2']);
        $carrier3 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier3']);
        $carrier4 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier4']);
        $carrier5 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier5']);


        $devvar1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device1->id,
            'priceRetail' => 100
        ]);
        $devvar2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier2->id,
            'deviceId' => $device2->id,
            'priceRetail' => 200
        ]);
        $devvar3 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier3->id,
            'deviceId' => $device3->id,
            'priceRetail' => 300
        ]);
        $devvar4 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier4->id,
            'deviceId' => $device4->id,
            'priceRetail' => 400
        ]);
        $devvar5 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier5->id,
            'deviceId' => $device5->id,
            'priceRetail' => 500
        ]);

        $resArray = (array)json_decode($this->json('GET', '/devices?include=devicevariations,devicevariations.carriers&filter[]=[devicevariations.carrierId]=' . $carrier1->id . '[or][devicevariations.carrierId]=' . $carrier3->id . '[or][devicevariations.carrierId]=' . $carrier5->id)->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsIncludesORMoreThanTwo: " . print_r($resArray, true));
        $this->assertCount(3, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device3->id);
        $this->assertEquals($resArray['data'][2]->id, $device5->id);
        $this->assertEquals($resArray['meta']->filter, ['[0]=[devicevariations.carrierId]=1[or][devicevariations.carrierId]=3[or][devicevariations.carrierId]=5']);
        $this->assertCount(6, $resArray['included']);
    }

    public function testFilterModelByItsIncludesGrandchildren() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device3']);
        $device4 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device4']);
        $device5 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device5']);

        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier1']);
        $carrier2 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier2']);
        $carrier3 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier3']);
        $carrier4 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier4']);
        $carrier5 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier5']);


        $devvar1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device1->id,
            'priceRetail' => 100
        ]);
        $devvar2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier2->id,
            'deviceId' => $device2->id,
            'priceRetail' => 200
        ]);
        $devvar3 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device3->id,
            'priceRetail' => 300
        ]);
        $devvar4 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier4->id,
            'deviceId' => $device4->id,
            'priceRetail' => 400
        ]);
        $devvar5 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device5->id,
            'priceRetail' => 500
        ]);

        $resArray = (array)json_decode($this->json('GET', '/devices?include=devicevariations,devicevariations.carriers&filter[devicevariations.carriers.name]=' . $carrier1->name)->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsIncludesGrandchildren: " . print_r($resArray, true));
        $this->assertCount(3, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device3->id);
        $this->assertEquals($resArray['data'][2]->id, $device5->id);
        $this->assertEquals($resArray['meta']->filter, ['[devicevariations.carriers.name]=' . $carrier1->name]);
        $this->assertCount(4, $resArray['included']);
    }

    public function testFilterModelByItsIncludesANDGrandchildren() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device3']);
        $device4 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device4']);
        $device5 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device5']);

        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier1']);
        $carrier2 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier2']);
        $carrier3 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier3']);
        $carrier4 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier4']);
        $carrier5 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier5']);


        $devvar1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device1->id,
            'priceRetail' => 100
        ]);
        $devvar2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier2->id,
            'deviceId' => $device2->id,
            'priceRetail' => 200
        ]);
        $devvar3 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier3->id,
            'deviceId' => $device3->id,
            'priceRetail' => 300
        ]);
        $devvar4 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier4->id,
            'deviceId' => $device4->id,
            'priceRetail' => 400
        ]);
        $devvar5 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier5->id,
            'deviceId' => $device5->id,
            'priceRetail' => 500
        ]);

        $resArray = (array)json_decode($this->json('GET', '/devices?include=devicevariations,devicevariations.carriers&filter[devicevariations.carriers.name]=' . $carrier1->name . ',' . $carrier2->name)->response->getContent());
        //\Log::debug("ApiFiltersTest@testFilterModelByItsIncludesANDGrandchildren: " . print_r($resArray, true));
        $this->assertCount(2, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device2->id);
        $this->assertEquals($resArray['meta']->filter, ['[devicevariations.carriers.name]=' . $carrier1->name . ',' . $carrier2->name]);
        $this->assertCount(4, $resArray['included']);
    }

        public function testFilterModelByItsIncludesORGrandchildren() {

        $device1 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device1']);
        $device2 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device2']);
        $device3 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device3']);
        $device4 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device4']);
        $device5 = factory(\WA\DataStore\Device\Device::class)->create(['name' => 'device5']);

        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier1']);
        $carrier2 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier2']);
        $carrier3 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier3']);
        $carrier4 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier4']);
        $carrier5 = factory(\WA\DataStore\Carrier\Carrier::class)->create(['name' => 'carrier5']);


        $devvar1 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device1->id,
            'priceRetail' => 100
        ]);
        $devvar2 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier2->id,
            'deviceId' => $device2->id,
            'priceRetail' => 200
        ]);
        $devvar3 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier3->id,
            'deviceId' => $device3->id,
            'priceRetail' => 300
        ]);
        $devvar4 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier1->id,
            'deviceId' => $device4->id,
            'priceRetail' => 400
        ]);
        $devvar5 = factory(\WA\DataStore\DeviceVariation\DeviceVariation::class)->create([
            'carrierId' => $carrier5->id,
            'deviceId' => $device5->id,
            'priceRetail' => 500
        ]);

        $resArray = (array)json_decode($this->json('GET', '/devices?include=devicevariations,devicevariations.carriers&filter[]=[devicevariations.carriers.name]=' . $carrier1->name . '[or][devicevariations.carriers.name]=' . $carrier2->name)->response->getContent());
        \Log::debug("ApiFiltersTest@testFilterModelByItsIncludesORGrandchildren: " . print_r($resArray, true));
        $this->assertCount(3, $resArray['data']);
        $this->assertEquals($resArray['data'][0]->id, $device1->id);
        $this->assertEquals($resArray['data'][1]->id, $device2->id);
        $this->assertEquals($resArray['data'][2]->id, $device4->id);
        $this->assertEquals($resArray['meta']->filter, ['[0]=[devicevariations.carriers.name]=' . $carrier1->name . '[or][devicevariations.carriers.name]=' . $carrier2->name]);
        $this->assertCount(5, $resArray['included']);
    }
}
