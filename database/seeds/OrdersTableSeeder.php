<?php

/**
 * OrdersTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class OrdersTableSeeder extends BaseTableSeeder
{
    protected $table = 'orders';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        factory(\WA\DataStore\Order\Order::class, 10)->create();
    }
}
