<?php

class JobsApiTest extends TestCase
{
    use \Laravel\Lumen\Testing\DatabaseMigrations;

    public function testSuccessfulUpdateToBillMonth()
    {

        //Create the necessary table entries and relationships to run the SQL query for the job that gets the current bill dates

        $allocation = factory(\WA\DataStore\Allocation\Allocation::class)->create();

        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        $allocation->carriers()->associate($carrier1);
        $allocation->save();

        $company1 = factory(\WA\DataStore\Company\Company::class)->create();
        $allocation->companies()->associate($company1);
        $allocation->save();

        $response = $this->call('PUT', '/jobs/updateBillingMonths');
        $this->assertEquals(200, $response->status());
    }

    public function testUnsuccessfulUpdateToBillMonth()
    {

        //Only allocations table created but the corresponding carrier and company table not created, without which the query needed for the job should fail
        $allocation = factory(\WA\DataStore\Allocation\Allocation::class)->create();

        $response = $this->call('PUT', '/jobs/updateBillingMonths');
        $this->assertEquals(400, $response->status());

    }




}