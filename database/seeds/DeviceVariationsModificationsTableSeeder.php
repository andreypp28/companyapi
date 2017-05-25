<?php

/**
 * DeviceVariationsModificationsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class DeviceVariationsModificationsTableSeeder extends BaseTableSeeder
{
    protected $table = 'device_variations_modifications';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();
        $i = 1;
        while ($i < 1001) {
            $dataStyle = [
                [
                    'deviceVariationId' => $i,
                    'modificationId' => rand(1,5),
                ]
            ];

            $this->loadTable($dataStyle);

            $dataCapacity = [
                [
                    'deviceVariationId' => $i,
                    'modificationId' => rand(6,10),
                ]
            ];

            $this->loadTable($dataCapacity);

            $i++;
        }
    }
}
