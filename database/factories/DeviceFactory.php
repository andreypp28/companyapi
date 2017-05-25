<?php
/*
|--------------------------------------------------------------------------
| Devices: Representation of devices we manage
|
| Device Types: types of devices we manage
|--------------------------------------------------------------------------
|
*/

$factory->define(\WA\DataStore\DeviceType\DeviceType::class,
    function () {
        return []; // Workaround, as this factory is not working for unknown reasons
        /*
        $makes = ['Apple', 'Samsung', 'Blackberry'];
        $models = ['Galaxy S7', 'IPhone SE', 'Q10', 'IPad Air'];
        $class = ['Phone', 'Tablet', 'M2M'];

        return [
            'make' => $makes[array_rand($makes)],
            'model' => $models[array_rand($models)],
            'class' => $class[array_rand($class)],
        ];
        */
    }
);

$factory->define(\WA\DataStore\Device\Device::class,
    function (\Faker\Generator $faker) {
        $names = ['iPhone 5', 'iPhone 5S', 'iPhone 6', 'iPhone 6S', 'Samsung G6', 'Samsung G7', 'Huawei g620s'];
        $currency = ['USD','EUR', 'GBP'];
        $makes = ['Apple', 'Samsung', 'Blackberry'];
        $models = ['Galaxy S7', 'IPhone SE', 'Q10', 'IPad Air'];
        $deviceTypeIds = [1,2,3,4,5,6];
        return [
            'identification' => $faker->isbn13,
            'name' => $names[array_rand($names)],
            'properties' => 'properties',
            //'externalId' => 1,
            'syncId' => 1,
            'statusId' => 1,
            'make' => $makes[array_rand($makes)],
            'model' => $models[array_rand($models)],
            'defaultPrice' => $faker->numberBetween(99, 501),
            'currency' => $currency[array_rand($currency)],
            'deviceTypeId' => $deviceTypeIds[array_rand($deviceTypeIds)]
            
        ];
    }
);

//NOTA: function (\Faker\Generator $faker) ???
