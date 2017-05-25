<?php

$factory->define(\WA\DataStore\DeviceVariation\DeviceVariation::class,
    function (\Faker\Generator $faker) {
        
        //$device = factory(\WA\DataStore\Device\Device::class)->create();
        //$carrier = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        //$company = factory(\WA\DataStore\Company\Company::class)->create();

        return [
            'deviceId' => $faker->numberBetween(1, 20),
            'carrierId' => $faker->numberBetween(1, 30),
            'companyId' => 1,
            'priceRetail' => $faker->numberBetween(300, 599),
            'price1' => $faker->numberBetween(300, 599),
            'price2' => $faker->numberBetween(300, 599),
            'priceOwn' => $faker->numberBetween(300, 599),
        ];
    }
);
