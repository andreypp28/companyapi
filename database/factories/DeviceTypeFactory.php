<?php

$factory->define(\WA\DataStore\DeviceType\DeviceType::class, 
	function ($faker) {
		$types = ['Smartphone','Tablet','Computer','Headphones','Phone Charger','Sim Card'];

		return [
			'name' => $types[array_rand($types)],
	        'statusId' => 1
	    ];
	}
);
