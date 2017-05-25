<?php

/**
 * PackageServicesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class PackageServicesTableSeeder extends BaseTableSeeder
{
    protected $table = 'package_services';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();
        $i = 1;
        while ($i < 900) {
            $data = [
                [
                    'packageId' => rand(1,39),
                    'serviceId' => rand(1,900)
                ]
            ];

            $this->loadTable($data);
            $i++;
        }
    }
}
