<?php


class DeviceTypeSeeder extends BaseTableSeeder
{
    protected $table = 'device_types';

    public function run()
    {
        $this->deleteTable();

        $dataDeviceType = [
            [
                'name' => 'Smartphone',
                'statusId' => 1,
            ],
            [
                'name' => 'Tablet',
                'statusId' => 1,
            ],
            [
                'name' => 'Computer',
                'statusId' => 1,
            ],
            [
                'name' => 'Headphones',
                'statusId' => 1,
            ],
            [
                'name' => 'Phone Charger',
                'statusId' => 1,
            ],
            [
                'name' => 'Sim Card',
                'statusId' => 1,
            ]
        ];
        $this->loadTable($dataDeviceType);
    }
}
