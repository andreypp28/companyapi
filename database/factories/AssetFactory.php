<?php
/*
|--------------------------------------------------------------------------
| Assets: Representation of companies that we manage
|
| Assets Types: What types of assets are represented
|--------------------------------------------------------------------------
|
*/

$factory->define(\WA\DataStore\AssetType::class, function () {
    return [
        'name' => 'mobile_number',
        'description' => 'An Mobile Number Asset type',
    ];
});

$factory->define(\WA\DataStore\Asset\Asset::class, function (\Faker\Generator $faker) {
    return [
        'identification' => $faker->e164PhoneNumber,
        'active' => 1,
        'userId' => rand(1,20),
        'typeId' => function () {
            if (!$assets = app()->make(\WA\DataStore\AssetType::class)->first()) {
                return factory(\WA\DataStore\AssetType::class)->create()->id;
            }

            return $assets->id;
        },

        'carrierId' => function () {
            return factory(WA\DataStore\Location\Location::class)->create()->id;
        },

        'updated_at' => $faker->dateTime,
        'created_at' => $faker->dateTime,
    ];
});
