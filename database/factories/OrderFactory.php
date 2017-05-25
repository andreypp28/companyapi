<?php


$factory->define(WA\DataStore\Order\Order::class, function ($faker) {
    $status = ['Approval', 'New', 'Delivered', 'Expired'];

    return [
        'status' => $status[array_rand($status)],
        'userId' => rand(1,20),
        'packageId' => rand(1,20),
        'serviceId' => rand(1,20),
        'addressId' => rand(1,20),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
