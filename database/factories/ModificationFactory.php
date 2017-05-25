<?php


$factory->define(WA\DataStore\Modification\Modification::class, function ($faker) {
    $modifications = ['capacity', 'style'];

    return [
        'modType' => $modifications[array_rand($modifications)],
        'value' => $faker->sentence,
        'updated_at' => $faker->dateTime,
        'created_at' => $faker->dateTime,
    ];
});
