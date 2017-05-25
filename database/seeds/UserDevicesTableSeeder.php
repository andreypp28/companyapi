<?php

class UserDevicesTableSeeder extends BaseTableSeeder
{
    protected $table = 'user_devices';

    public function run()
    {
        $this->deleteTable();

        $data = [
            [
                'userId' => 1,
                'deviceId' => 5
            ],
            [
                'userId' => 1,
                'deviceId' => 12
            ],
            [
                'userId' => 1,
                'deviceId' => 27
            ],
            [
                'userId' => 1,
                'deviceId' => 39
            ],
            [
                'userId' => 2,
                'deviceId' => 5
            ],
            [
                'userId' => 2,
                'deviceId' => 12
            ],
            [
                'userId' => 2,
                'deviceId' => 27
            ],
            [
                'userId' => 2,
                'deviceId' => 39
            ],
            [
                'userId' => 3,
                'deviceId' => 5
            ],
            [
                'userId' => 3,
                'deviceId' => 12
            ],
            [
                'userId' => 3,
                'deviceId' => 27
            ],
            [
                'userId' => 3,
                'deviceId' => 39
            ],
        ];

        $this->loadTable($data);
    }
}