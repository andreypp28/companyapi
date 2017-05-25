<?php

$factory->define(WA\DataStore\User\UserAddress::class, function ($faker) {
    return [
        'userId' => $faker->numberBetween(1, 5),
        'addressId' => $faker->numberBetween(1, 5)
    ];
});
