<?php

$factory->define(WA\DataStore\Role\Role::class, function ($faker) {
    return [
        'name' => $faker->sentence,
        'display_name' => $faker->sentence,
        'description' => $faker->paragraph
    ];
});
