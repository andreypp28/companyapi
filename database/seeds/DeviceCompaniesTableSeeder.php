<?php

/**
 * DeviceCompaniesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class DeviceCompaniesTableSeeder extends BaseTableSeeder
{
    protected $table = 'device_companies';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'deviceId' => 1,
                'companyId' => 1,
            ],
            [
                'deviceId' => 1,
                'companyId' => 2,
            ],
            [
                'deviceId' => 1,
                'companyId' => 3,
            ],
            [
                'deviceId' => 2,
                'companyId' => 1,
            ],
            [
                'deviceId' => 2,
                'companyId' => 4,
            ],
            [
                'deviceId' => 2,
                'companyId' => 5,
            ],
            [
                'deviceId' => 3,
                'companyId' => 2,
            ],
            [
                'deviceId' => 3,
                'companyId' => 5,
            ],
            [
                'deviceId' => 3,
                'companyId' => 6,
            ],
        ];

        $this->loadTable($data);
    }
}
