<?php

/**
 * OrderDevicesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class OrderDeviceVariationsTableSeeder extends BaseTableSeeder
{
    protected $table = 'order_device_variations';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'orderId' => 1,
                'deviceVariationId' => 1,
            ],
            [
                'orderId' => 1,
                'deviceVariationId' => 2,
            ],
            [
                'orderId' => 1,
                'deviceVariationId' => 3,
            ],
            [
                'orderId' => 1,
                'deviceVariationId' => 4,
            ],
            [
                'orderId' => 1,
                'deviceVariationId' => 5,
            ],
            [
                'orderId' => 1,
                'deviceVariationId' => 6,
            ],
            [
                'orderId' => 1,
                'deviceVariationId' => 7,
            ],
            [
                'orderId' => 1,
                'deviceVariationId' => 8,
            ],
            [
                'orderId' => 2,
                'deviceVariationId' => 1,
            ],
            [
                'orderId' => 2,
                'deviceVariationId' => 2,
            ],
            [
                'orderId' => 2,
                'deviceVariationId' => 3,
            ],
            [
                'orderId' => 2,
                'deviceVariationId' => 4,
            ],
            [
                'orderId' => 2,
                'deviceVariationId' => 5,
            ],
            [
                'orderId' => 2,
                'deviceVariationId' => 6,
            ],
            [
                'orderId' => 2,
                'deviceVariationId' => 7,
            ],
            [
                'orderId' => 2,
                'deviceVariationId' => 8,
            ],
            [
                'orderId' => 3,
                'deviceVariationId' => 1,
            ],
            [
                'orderId' => 3,
                'deviceVariationId' => 2,
            ],
            [
                'orderId' => 3,
                'deviceVariationId' => 3,
            ],
            [
                'orderId' => 3,
                'deviceVariationId' => 4,
            ],
            [
                'orderId' => 3,
                'deviceVariationId' => 5,
            ],
            [
                'orderId' => 3,
                'deviceVariationId' => 6,
            ],
            [
                'orderId' => 3,
                'deviceVariationId' => 7,
            ],
            [
                'orderId' => 3,
                'deviceVariationId' => 8,
            ]
        ];

        $this->loadTable($data);
    }
}
