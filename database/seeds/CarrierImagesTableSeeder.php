<?php

/**
 * CarrierImagesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class CarrierImagesTableSeeder extends BaseTableSeeder
{
    protected $table = 'carrier_images';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();
        $i = 1;
        while ($i < 31) {
            $data = [
                [
                    'carrierId' => $i,
                    'imageId' => rand(5,8)
                ]
            ];

            $this->loadTable($data);
            $i++;
        }
    }
}
