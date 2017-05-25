<?php


$factory->define(WA\DataStore\Service\Service::class, function ($faker) {
    $currency = ['USD', 'EUR', 'GBP'];

    return [
        'status' => 'Enabled',
        'title' => $faker->sentence,
        'planCode' => $faker->numberBetween(11111, 99999),
        'cost' => $faker->numberBetween(100, 199),
        'description' => $faker->paragraph,
        'currency' => $currency[array_rand($currency)],
        'carrierId' => $faker->numberBetween(1, 30),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
