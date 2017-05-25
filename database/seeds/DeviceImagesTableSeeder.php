<?php

/**
 * DeviceImagesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class DeviceImagesTableSeeder extends BaseTableSeeder
{
    protected $table = 'device_images';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'deviceId' => 1,
                'imageId' => 1,
            ],
            [
                'deviceId' => 1,
                'imageId' => 2,
            ],
            [
                'deviceId' => 2,
                'imageId' => 1,
            ],
            [
                'deviceId' => 2,
                'imageId' => 4,
            ],
            [
                'deviceId' => 3,
                'imageId' => 1,
            ],
            [
                'deviceId' => 3,
                'imageId' => 5,
            ],
        ];

        $this->loadTable($data);
    }
}
