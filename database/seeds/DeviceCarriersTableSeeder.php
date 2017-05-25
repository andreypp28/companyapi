<?php

/**
 * DeviceCarriersTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class DeviceCarriersTableSeeder extends BaseTableSeeder
{
    protected $table = 'device_carriers';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'deviceId' => 1,
                'carrierId' => 1,
            ],
            [
                'deviceId' => 1,
                'carrierId' => 2,
            ],
            [
                'deviceId' => 1,
                'carrierId' => 3,
            ],
            [
                'deviceId' => 2,
                'carrierId' => 1,
            ],
            [
                'deviceId' => 2,
                'carrierId' => 5,
            ],
            [
                'deviceId' => 2,
                'carrierId' => 6,
            ],
            [
                'deviceId' => 3,
                'carrierId' => 2,
            ],
            [
                'deviceId' => 3,
                'carrierId' => 11,
            ],
            [
                'deviceId' => 3,
                'carrierId' => 13,
            ],
        ];

        $this->loadTable($data);
    }
}
