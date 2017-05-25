<?php

class LocationsTableSeeder extends BaseTableSeeder
{
    protected $table = 'locations';

    public function run()
    {
        $this->deleteTable();

        $data = [
            [
                'name' => "",
                'fullName' => "",
                'iso2' => "",
                'iso3' => "",
                'region' => "",
                'currency' => "",
                'numCode' => "",
                'callingCode' => "",
                'lang' => "",
                'currencyIso' => ""
            ]
        ];

        $this->loadTable($data);
    }
}
