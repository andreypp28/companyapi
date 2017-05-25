<?php

/**
 * PackageAppsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class PackageAppsTableSeeder extends BaseTableSeeder
{
    protected $table = 'package_apps';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'packageId' => 1,
                'appId' => 1,
            ],
            [
                'packageId' => 1,
                'appId' => 2,
            ],
            [
                'packageId' => 1,
                'appId' => 3,
            ],
            [
                'packageId' => 1,
                'appId' => 4,
            ],
        ];

        $this->loadTable($data);
    }
}
