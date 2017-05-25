<?php

/*
|--------------------------------------------------------------------------
| Locations
|--------------------------------------------------------------------------
|
*/
$factory->define(\WA\DataStore\Location\Location::class, function (\Faker\Generator $faker) {
    return [
        'name' => $faker->country,
        'fullName' => $faker->country,
        'iso2' => $faker->countryISOAlpha3,
        'iso3' => $faker->countryISOAlpha3,
        'country' => $faker->country,
        'city' => $faker->sentence,
        'zipCode' => $faker->biasedNumberBetween(10000, 50000),
        'address' => $faker->sentence
    ];
});
