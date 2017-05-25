<?php

/**
 * ModificationsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class ModificationsTableSeeder extends BaseTableSeeder
{
    protected $table = 'modifications';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [
            [
                'modType' => 'style',
                'value' => 'White',
            ],
            [
                'modType' => 'style',
                'value' => 'Gold',
            ],
            [
                'modType' => 'style',
                'value' => 'Space Grey',
            ],
            [
                'modType' => 'style',
                'value' => 'Black',
            ],
            [
                'modType' => 'style',
                'value' => 'Silver',
            ],
            [
                'modType' => 'capacity',
                'value' => '8Gb',
            ],
            [
                'modType' => 'capacity',
                'value' => '16Gb',
            ],
            [
                'modType' => 'capacity',
                'value' => '32Gb',
            ],
            [
                'modType' => 'capacity',
                'value' => '64Gb',
            ],
            [
                'modType' => 'capacity',
                'value' => '128Gb',
            ],

        ];

        $this->loadTable($data);
    }
}
