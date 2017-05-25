<?php

$factory->define(WA\DataStore\Address\Address::class, function ($faker) {

	$greekgods = ['Zeus', 'Hera', 'Poseidón', 'Deméter', 'Hestia', 'Hades', 'Ares', 'Hermes', 'Hefesto', 'Atenea', 'Apolo', 'Artemisa', 'Cárites', 'Heracles', 'Dioniso', 'Hebe', 'Perseo', 'Perséfone'];
    $cities = ['Shanghai', 'Delhi', 'Moscow', 'Tokyo', 'São Paulo', 'London', 'New York', 'Barcelona'];
    $country = ['China', 'India', 'Russia', 'Japan', 'Brazil', 'United Kingdom', 'United States', 'Catalonia'];

    return [
    	'name' => $greekgods[array_rand($greekgods)],
        'attn' => $faker->sentence,
        'phone' => $faker->numberBetween(600000000, 699999999),
        'address' => $faker->sentence,
        'city' => $cities[array_rand($cities)],
        'state' => $faker->sentence,
        'country' => $country[array_rand($country)],
        'postalCode' => $faker->numberBetween(11111, 99999),
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
