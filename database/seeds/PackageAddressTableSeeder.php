<?php

class PackageAddressTableSeeder extends BaseTableSeeder
{
    protected $table = 'package_address';

    public function run()
    {
        $this->deleteTable();
        $i = 1;
        while ($i < 100) {
            $data = [
                [
                    'packageId' => rand(1,5),
                    'addressId' => $i
                ]
            ];

            $this->loadTable($data);
            $i++;
        }        
    }
}