<?php

$factory->define(WA\DataStore\ServiceItem\ServiceItem::class, function ($faker) {
    return [
        'serviceId' => $faker->numberBetween(1, 4),
		'category' => $faker->sentence,
		'description' => $faker->sentence,
		'value' => $faker->sentence,
		'unit' => $faker->sentence,
		'cost' => $faker->sentence,
		'domain' => $faker->sentence,
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
