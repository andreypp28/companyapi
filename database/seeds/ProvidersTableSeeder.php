<?php

/**
 * ProvidersTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class ProvidersTableSeeder extends BaseTableSeeder
{
    protected $table = 'providers';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'name' => 'Thermotronic',
                'user' => 'Antonio Olson',
                'code' => '123-321-1234',
            ],
            [
                'name' => 'Cocoa',
                'user' => 'Saturnino Garcia',
                'code' => '773-994-2673',
            ],
            [
                'name' => 'Pepsi',
                'user' => 'Eufemiano Perezoso',
                'code' => '367-334-3453',
            ],
            [
                'name' => 'SirionDevelopers',
                'user' => 'Agusti Dosaiguas',
                'code' => '512-105-0605',
            ],
        ];

        $this->loadTable($data);
    }
}
