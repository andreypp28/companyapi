<?php

class PresetDeviceVariationTableSeeder extends BaseTableSeeder
{
    protected $table = 'preset_device_variations';

    public function run()
    {
        $this->deleteTable();
        $i = 1;
        while ($i < 1001) {
            $data = [
                [
                    'presetId' => rand(1,70),
                    'deviceVariationId' => $i
                ]
            ];

            $this->loadTable($data);
            $i++;
        }
        
    }
}