<?php

/**
 * ScopesTableSeeder - Insert info into database.
 *
 * @author   Marcio de Rezende
 */
class ScopesTableSeeder extends BaseTableSeeder
{
    protected $table = 'scopes';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'name' => 'RolePermisScope',
                'display_name' => 'RolePermisScope',
                'description' => 'Power to administrate the Role, Permitions and Scopes',
                
            ],
            [
                'name' => 'get',
                'display_name' => 'get',
                'description' => 'Can find a specific id ',
                
            ],
            [
                'name' => 'gets',
                'display_name' => 'gets',
                'description' => 'Can find all',
                
            ],
            [
                'name' => 'post',
                'display_name' => 'post',
                'description' => 'Can create',
                
            ],
            [
                'name' => 'put',
                'display_name' => 'put',
                'description' => 'Can do a update',
                
            ],
            [
                'name' => 'delete',
                'display_name' => 'delete',
                'description' => 'Can delete',
                
            ],
        ];

        $this->loadTable($data);
    }
}