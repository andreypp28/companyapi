<?php

/**
 * OrderAppsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class OrderAppsTableSeeder extends BaseTableSeeder
{
    protected $table = 'order_apps';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'orderId' => 1,
                'appId' => 1,
            ],
            [
                'orderId' => 1,
                'appId' => 2,
            ],
            [
                'orderId' => 2,
                'appId' => 3,
            ],
            [
                'orderId' => 3,
                'appId' => 4,
            ],
        ];

        $this->loadTable($data);
    }
}
