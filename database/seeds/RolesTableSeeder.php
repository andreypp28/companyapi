<?php

/*
 * RolesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class RolesTableSeeder extends BaseTableSeeder
{
    protected $table = 'roles';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'name' => 'superAdmin',
                'display_name' => 'superAdmin',
                'description' => 'User with full power and access',
                
            ],
            [
                'name' => 'admin',
                'display_name' => 'admin',
                'description' => 'Administrator of the company account',
            ],
            [
                'name' => 'wta',
                'display_name' => 'wta',
                'description' => 'Accept or no the services',
            ],
            [
               'name' => 'user',
                'display_name' => 'user',
                'description' => 'Normal user',
            ]
        ];

        $this->loadTable($data);
    }
}
