<?php

$factory->define(WA\DataStore\App\App::class, function ($faker) {
    return [
        'type' => $faker->sentence,
        'image' => $faker->sentence,
        'description' => $faker->paragraph,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
