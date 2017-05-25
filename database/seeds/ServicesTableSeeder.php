<?php

/**
 * ServicesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class ServicesTableSeeder extends BaseTableSeeder
{
    protected $table = 'services';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();
        factory(\WA\DataStore\Service\Service::class, 900)->create();
    }
}
