<?php

$factory->define(WA\DataStore\User\UserUdlValue::class, function ($faker) {
    return [
        'userId' => $faker->numberBetween(1, 5),
        'udlValueId' => $faker->numberBetween(1, 5)
    ];
});
