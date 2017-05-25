<?php

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
|
*/
$factory->define(WA\DataStore\User\User::class, function (Faker\Generator $faker) {
    return [
        'uuid' => $faker->uuid,
        'identification' => uniqid(strtoupper(str_random(2)) . '-'),
        'email' => $email = $faker->safeEmail,
        'alternateEmail' => $faker->safeEmail,
        'password' => 'user',
        'username' => explode('@', $email)[0],
        'confirmation_code' => md5(uniqid(mt_rand(), true)),
        'remember_token' => null,
        'confirmed' => 1,
        'firstName' => $faker->firstName,
        'lastName' => $faker->lastName,
        'alternateFirstName' => $faker->firstName,
        'supervisorEmail' => $faker->safeEmail,
        'companyUserIdentifier' => $faker->numberBetween(0, 5),
        'isSupervisor' => $faker->numberBetween(0, 1),
        'isValidator' => $faker->numberBetween(0, 1),
        'isActive' => 1,
        'rgt' => null,
        'lft' => null,
        'hierarchy' => null,
        'defaultLang' => 'en',
        'notes' => null,
        'level' => 0,
        'notify' => 0,
        'companyId' => $faker->numberBetween(2, 5),
        'syncId' => null,
        'supervisorId' => $faker->numberBetween(1, 5),
        'externalId' => null,
        'approverId' => $faker->numberBetween(1, 5),
        'defaultLocationId' => function () {
            return factory(\WA\DataStore\Location\Location::class)->create()->id;
        },
        'deleted_at' => null,
        'created_at' => null,
        'updated_at' => null
    ];
});
