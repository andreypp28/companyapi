<?php

$factory->define(WA\DataStore\UdlValue\UdlValue::class, function ($faker) {
    return [
        'name' => $faker->sentence,
        'udlId' => $faker->numberBetween(1, 5),
        'externalId' => $faker->numberBetween(1, 5),
    ];
});
