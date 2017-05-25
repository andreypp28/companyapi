<?php

$factory->define(WA\DataStore\Permission\Permission::class, function ($faker) {
	
    return [
    	
        'name' => $faker->sentence,
        'display_name' => $faker->sentence,
        'description' => $faker->paragraph
    ];
});