<?php

/**
 * ConditionsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class ConditionsTableSeeder extends BaseTableSeeder
{
    protected $table = 'conditions';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'packageId' => 1,
                'name' => 'Supervisor?',
                'condition' => 'equal',
                'value' => 'No',
            ],
            [
                'packageId' => 1,
                'name' => 'Hierarchy',
                'condition' => 'contains',
                'value' => '1',
            ],
            [
                'packageId'  => 1,
                'name'      => "Level",
                'condition' => "greater than",
                'value'     => "2"
            ],
            [
                'packageId' => 2,
                'name' => 'Supervisor?',
                'condition' => 'equal',
                'value' => 'No',
            ],
            [
                'packageId' => 2,
                'name' => 'Position',
                'condition' => 'contains',
                'value' => 'Boss',
            ],
            [
                'packageId'  => 2,
                'name'      => "Level",
                'condition' => "greater than",
                'value'     => "2"
            ],
            [
                'packageId' => 3,
                'name' => 'Supervisor?',
                'condition' => 'equal',
                'value' => 'No',
            ],
            [
                'packageId' => 3,
                'name' => 'Hierarchy',
                'condition' => 'contains',
                'value' => 'Boss',
            ],
            [
                'packageId'  => 3,
                'name'      => "Level",
                'condition' => "greater than",
                'value'     => "2"
            ],
            [
                'packageId' => 3,
                'name' => 'Country',
                'condition' => 'equal',
                'value' => 'Europe',
            ],
            [
                'packageId' => 3,
                'name' => 'State',
                'condition' => 'contains',
                'value' => 'Cat',
            ],
            [
                'packageId'  => 3,
                'name'      => "City",
                'condition' => "contains",
                'value'     => "Bar"
            ],
            [
                'packageId' => 3,
                'name' => 'Cost Center',
                'condition' => 'equal',
                'value' => '122',
            ],
            [
                'packageId' => 3,
                'name' => 'Division',
                'condition' => 'contains',
                'value' => 'div',
            ],
            [
                'packageId'  => 3,
                'name'      => "Position",
                'condition' => "greater than",
                'value'     => "2"
            ],
        ];

        $this->loadTable($data);
    }
}
