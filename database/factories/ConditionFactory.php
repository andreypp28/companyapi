<?php


$factory->define(WA\DataStore\Condition\Condition::class, function ($faker) {
    $name = ['name', 'email', 'position', 'level', 'division', 'costCenter', 'budget', 'country', 'city', 'address'];
    $condition = ['isAny', 'contains', 'isGreaterThan', 'isLessThan'];
    $value = ['engineer', '3', 'Sales', '600', 'USA', 'Canada'];

    return [
        'packageId' => 1,
        'name' => $name[array_rand($name)],
        'condition' => $condition[array_rand($condition)],
        'value' => $value[array_rand($value)],
    ];
});
