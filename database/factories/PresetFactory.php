<?php

$factory->define(WA\DataStore\Preset\Preset::class, function (\Faker\Generator $faker) {

    return [
        'name' => 'Preset'.random_int(1, 60),
        'companyId' => 1,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
