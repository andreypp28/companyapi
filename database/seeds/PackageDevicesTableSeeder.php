<?php

/**
 * PackageDevicesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class PackageDevicesTableSeeder extends BaseTableSeeder
{
    protected $table = 'package_devices';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'packageId' => 1,
                'deviceVariationId' => 1,
            ],
            [
                'packageId' => 1,
                'deviceVariationId' => 2,
            ],
            [
                'packageId' => 1,
                'deviceVariationId' => 3,
            ],
            [
                'packageId' => 1,
                'deviceVariationId' => 4,
            ],
            [
                'packageId' => 2,
                'deviceVariationId' => 5,
            ],
            [
                'packageId' => 2,
                'deviceVariationId' => 6,
            ],
            [
                'packageId' => 2,
                'deviceVariationId' => 7,
            ],
            [
                'packageId' => 2,
                'deviceVariationId' => 8,
            ],
            [
                'packageId' => 5,
                'deviceVariationId' => 2,
            ],
            [
                'packageId' => 5,
                'deviceVariationId' => 3,
            ],
            [
                'packageId' => 5,
                'deviceVariationId' => 4,
            ],
            [
                'packageId' => 5,
                'deviceVariationId' => 5,
            ],
            [
                'packageId' => 5,
                'deviceVariationId' => 6,
            ],
            [
                'packageId' => 5,
                'deviceVariationId' => 7,
            ],
            [
                'packageId' => 5,
                'deviceVariationId' => 8,
            ],
        ];

        $this->loadTable($data);
    }
}
