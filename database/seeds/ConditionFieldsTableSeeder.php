<?php

/**
 * ConditionsFieldsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class ConditionFieldsTableSeeder extends BaseTableSeeder
{
    protected $table = 'condition_fields';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'typeField' => 'Employee Profile',
                'field' => 'Name',
            ],
            [
                'typeField' => 'Employee Profile',
                'field' => 'Email',
            ],
            [
                'typeField' => 'Employee Profile',
                'field' => 'Position',
            ],
            [
                'typeField' => 'Employee Profile',
                'field' => 'Level',
            ],
            [
                'typeField' => 'Employee Profile',
                'field' => 'Division',
            ],
            [
                'typeField' => 'Employee Profile',
                'field' => 'Cost Center',
            ],
            [
                'typeField' => 'Employee Profile',
                'field' => 'Budget',
            ],
            [
                'typeField' => 'Location',
                'field' => 'Country',
            ],
            [
                'typeField' => 'Location',
                'field' => 'Country',
            ],
            [
                'typeField' => 'Location',
                'field' => 'City',
            ],
            [
                'typeField' => 'Location',
                'field' => 'Address',
            ],
        ];

        $this->loadTable($data);
    }
}
