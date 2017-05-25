<?php

/**
 * DeviceImagesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class DeviceVariationImagesTableSeeder extends BaseTableSeeder
{
    protected $table = 'deviceVariation_images';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'deviceVariationId' => 1,
                'imageId' => 1,
            ],
            [
                'deviceVariationId' => 1,
                'imageId' => 2,
            ],
            [
                'deviceVariationId' => 2,
                'imageId' => 1,
            ],
            [
                'deviceVariationId' => 2,
                'imageId' => 4,
            ],
            [
                'deviceVariationId' => 3,
                'imageId' => 1,
            ],
            [
                'deviceVariationId' => 3,
                'imageId' => 5,
            ],
        ];

        $this->loadTable($data);
    }
}
