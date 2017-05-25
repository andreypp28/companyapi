<?php

$factory->define(WA\DataStore\Udl\Udl::class, function ($faker) {
    return [
    	'companyId' => $faker->numberBetween(1, 5),
        'name'  => $faker->sentence,
        'legacyUdlField' => $faker->sentence,
    ];
});
