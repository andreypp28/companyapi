<?php

class UserUdlsTableSeeder extends BaseTableSeeder
{
    protected $table = 'user_udls';

    public function run()
    {
        $this->deleteTable();

        $data = [
            [
                'userId' => 1,
                'udlValueId' => 5
            ],
            [
                'userId' => 1,
                'udlValueId' => 12
            ],
            [
                'userId' => 1,
                'udlValueId' => 27
            ],
            [
                'userId' => 1,
                'udlValueId' => 39
            ],
            [
                'userId' => 2,
                'udlValueId' => 5
            ],
            [
                'userId' => 2,
                'udlValueId' => 12
            ],
            [
                'userId' => 2,
                'udlValueId' => 27
            ],
            [
                'userId' => 2,
                'udlValueId' => 39
            ],
            [
                'userId' => 3,
                'udlValueId' => 5
            ],
            [
                'userId' => 3,
                'udlValueId' => 12
            ],
            [
                'userId' => 3,
                'udlValueId' => 27
            ],
            [
                'userId' => 3,
                'udlValueId' => 39
            ],
        ];

        $this->loadTable($data);
    }
}
