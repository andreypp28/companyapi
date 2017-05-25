<?php

/**
 * DeviceModificationsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class DeviceModificationsTableSeeder extends BaseTableSeeder
{
    protected $table = 'device_modifications';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'deviceId' => 1,
                'modificationId' => 1,
            ],
            [
                'deviceId' => 1,
                'modificationId' => 2,
            ],
            [
                'deviceId' => 1,
                'modificationId' => 3,
            ],
            [
                'deviceId' => 1,
                'modificationId' => 4,
            ],
            [
                'deviceId' => 2,
                'modificationId' => 1,
            ],
            [
                'deviceId' => 2,
                'modificationId' => 2,
            ],
            [
                'deviceId' => 2,
                'modificationId' => 3,
            ],
            [
                'deviceId' => 2,
                'modificationId' => 5,
            ],
                        [
                'deviceId' => 3,
                'modificationId' => 1,
            ],
            [
                'deviceId' => 3,
                'modificationId' => 2,
            ],
            [
                'deviceId' => 3,
                'modificationId' => 5,
            ],
            [
                'deviceId' => 3,
                'modificationId' => 6,
            ],
        ];

        $this->loadTable($data);
    }
}
