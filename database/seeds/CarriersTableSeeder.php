<?php

/**
 * CarriersTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class CarriersTableSeeder extends BaseTableSeeder
{
    protected $table = 'carriers';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();
        factory(\WA\DataStore\Carrier\Carrier::class, 30)->create();
    }
}
