<?php

$factory->define(\WA\DataStore\Carrier\Carrier::class, function ($faker) {
    $carriers = ['ATT', 'Verizon', 'T-Mobile', 'Sprint', 'iPass', 'Rogers', 'T-Mobile DE', 'US Cellular', 'System', 'BellCanada', 'VodafoneUK', 'VodafoneDE'];

    return [
        'presentation' => $name = $carriers[array_rand($carriers)],
        'name' => str_replace('-', '_', strtolower($name)),
        'active' => 1,
        'locationId' => function () {
            return factory(WA\DataStore\Location\Location::class)->create()->id;
        },
        'shortName' => $name,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});