<?php

/**
 * ConditionOperatorsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class ConditionOperatorsTableSeeder extends BaseTableSeeder
{
    protected $table = 'condition_operators';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'originalName' => 'contains',
                'apiName' => 'like',
            ],
            [
                'originalName' => 'is greater than',
                'apiName' => 'gt',
            ],
            [
                'originalName' => 'is less than',
                'apiName' => 'lt',
            ],
            [
                'originalName' => 'is greater or equal to',
                'apiName' => 'gte',
            ],
            [
                'originalName' => 'is less or equal to',
                'apiName' => 'lte',
            ],
            [
                'originalName' => 'is not equal to',
                'apiName' => 'ne',
            ],
            [
                'originalName' => 'is equal to',
                'apiName' => 'eq',
            ],
            [
                'originalName' => 'is any',
                'apiName' => '',
            ],
        ];

        $this->loadTable($data);
    }
}
