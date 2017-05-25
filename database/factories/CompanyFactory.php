<?php

$factory->define(WA\DataStore\Company\Company::class, function (\Faker\Generator $faker) {
    $bill_months = ['2016-05-01', '2016-06-01', '2016-07-01', '2016-08-01'];

    return [
        'name' => $company_name = $faker->company,
        'label' => str_replace(' ', '_', strtolower($company_name)),
        'active' => 1,
        'isCensus'=>0,
        'assetPath' => '/var/www/clean/storage/clients/clients/acme',
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
        'currentBillMonth' => $bill_months[array_rand($bill_months)],
        'shortName' => $faker->name,
        'defaultLocation' => $faker->country,
    ];
});
