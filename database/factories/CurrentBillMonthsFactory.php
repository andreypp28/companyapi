<?php

$factory->define(WA\DataStore\Company\CompanyCurrentBillMonth::class, function (\Faker\Generator $faker) {
    $bill_months = ['2016-05-01', '2016-06-01', '2016-07-01', '2016-08-01'];

    return [
        'carrierId' => $faker->numberBetween(1, 7),
        'companyId' =>  $faker->numberBetween(9, 25),
        'currentBillMonth' => $bill_months[array_rand($bill_months)],
    ];
});
