<?php

/**
 * ScopePermissionTableSeeder - Insert info into database.
 *
 * @author   Marcio de Rezende
 */
class ScopePermissionsTableSeeder extends BaseTableSeeder
{
    protected $table = 'scope_permission';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $dataRolePermisScope = [

            //RolePermisScope
                //Role
            [
                'scope_id' => 1,
                'permission_id' => 1,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 2,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 3,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 4,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 5,               
            ],
                //permissions
            [
                'scope_id' => 1,
                'permission_id' => 6,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 7,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 8,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 9,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 10,               
            ],
                 //Scope
            [
                'scope_id' => 1,
                'permission_id' => 11,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 12,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 13,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 14,               
            ],
            [
                'scope_id' => 1,
                'permission_id' => 15,               
            ],
           
        ];
        //GET
        $dataGet = [
            [
                'scope_id' => 3,
                'permission_id' => 16,    
            ],
            
        ];

        $this->loadTable($dataRolePermisScope);
    }
}
