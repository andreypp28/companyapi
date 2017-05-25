<?php


class DevicesTableSeeder extends BaseTableSeeder
{
    protected $table = 'devices';

    public function run()
    {
        $this->deleteTable();

        factory(\WA\DataStore\Device\Device::class, 21)->create();
        

                  /*  $dataDevice1 = [

            
            [
                        'name' => 'IOS1',
                        'properties' => 'Queen',
                        'deviceTypeId' => 1,
                        'statusId' => 1,
                        'externalId' => 2,
                        'identification' => rand(9000000000000, 9999999999999),
                    	'make' => 'Apple',
                    	'model' => 'IPHONE'
            ],
            [
                        'name' => 'IOS2',
                        'properties' => 'Queen',
                        'deviceTypeId' => 2,
                        'statusId' => 1,
                        'externalId' => 3,
                        'identification' => rand(9000000000000, 9999999999999),
                    	'make' => 'Apple',
                    	'model' => 'IPHONE2'
            ],
            [
                        'name' => 'IOS3',
                        'properties' => 'Queen',
                        'deviceTypeId' => 3,
                        'statusId' => 1,
                        'externalId' => 4,
                        'identification' => rand(9000000000000, 9999999999999),
                    	'make' => 'Apple',
                    	'model' => 'IPHONE3'
            ],
            [
                        'name' => 'IOS4',
                        'properties' => 'Queen',
                        'deviceTypeId' => 4,
                        'statusId' => 1,
                        'externalId' => 5,
                        'identification' => rand(9000000000000, 9999999999999),
                    	'make' => 'Apple',
                    	'model' => 'IPHONE4'
            ],
            [
                        'name' => 'IOS5',
                        'properties' => 'Queen',
                        'deviceTypeId' => 5,
                        'statusId' => 1,
                        'externalId' => 6,
                        'identification' => rand(9000000000000, 9999999999999),
                    	'make' => 'Apple',
                    	'model' => 'IPHONE5'
            ],
            [
                        'name' => 'SAMS1',
                        'properties' => 'MyOneSolutionIsMyQueen',
                        'deviceTypeId' => 6,
                        'statusId' => 1,
                        'externalId' => 7,
                        'identification' => rand(9000000000000, 9999999999999),
                    	'make' => 'Samsung',
                    	'model' => 'Samsung'
            ],
            [
                        'name' => 'SAMS2',
                        'properties' => 'MyOneSolutionIsMyQueen',
                        'deviceTypeId' => 7,
                        'statusId' => 1,
                        'externalId' => 8,
                        'identification' => rand(9000000000000, 9999999999999),
                    	'make' => 'Samsung',
                    	'model' => 'Samsung2'
            ],
            [
                        'name' => 'SAMS3',
                        'properties' => 'MyOneSolutionIsMyQueen',
                        'deviceTypeId' => 8,
                        'statusId' => 1,
                        'externalId' => 9,
                        'identification' => rand(9000000000000, 9999999999999),
                    	'make' => 'Samsung',
                    	'model' => 'Samsung3'
            ],
            [
                        'name' => 'SAMS4',
                        'properties' => 'MyOneSolutionIsMyQueen',
                        'deviceTypeId' => 9,
                        'statusId' => 1,
                        'externalId' => 21,
                        'identification' => rand(9000000000000, 9999999999999),
                    	'make' => 'Samsung',
                    	'model' => 'Samsung4'
            ],
            [
                        'name' => 'SAMS5',
                        'properties' => 'MyOneSolutionIsMyQueen',
                        'deviceTypeId' => 10,
                        'statusId' => 1,
                        'externalId' => 12,
                        'identification' => rand(9000000000000, 9999999999999),
                    	'make' => 'Samsung',
                    	'model' => 'Samsung52B'
            ],

        ];
        $this->loadTable($dataDevice1);*/
    }
}
