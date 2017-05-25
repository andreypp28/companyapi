<?php


$factory->define(WA\DataStore\Content\Content::class, function ($faker) {
	$user = factory(\WA\DataStore\User\User::class)->create();

    return [
        'content' => $faker->paragraph,
        'active' => 1,
        'owner_id' => 5,
        'owner_type' => 'company',
        'created_at' => $faker->dateTime,
        'updated_at' => $faker->dateTime,
    ];
});
