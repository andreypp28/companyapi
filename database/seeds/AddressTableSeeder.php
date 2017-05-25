<?php

/**
 * AddressTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class AddressTableSeeder extends BaseTableSeeder
{
    protected $table = 'address';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        factory(\WA\DataStore\Address\Address::class, 100)->create();
    }
}
