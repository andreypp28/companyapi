<?php

/**
 * DeviceVariantsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class DeviceVariationsTableSeeder extends BaseTableSeeder
{
    protected $table = 'device_variations';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();
        factory(\WA\DataStore\DeviceVariation\DeviceVariation::class, 1000)->create();

		//factory(\WA\DataStore\DeviceVariation\DeviceVariation::class, 3)->create(['deviceId' => 21]);

    }
}
