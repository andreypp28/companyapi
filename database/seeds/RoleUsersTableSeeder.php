<?php

/**
 * RoleUsersTableSeeder - Insert info into database.
 *
 * @author   Marcio de Rezende
 */
class RoleUsersTableSeeder extends BaseTableSeeder
{
    protected $table = 'role_user';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            //companyAdmin
            [
                'user_id' => 1,
                'role_id' => 1,               
            ],
            [
                'user_id' => 2,
                'role_id' => 2,               
            ],
             [
                'user_id' => 3,
                'role_id' => 3,               
            ],
             [
                'user_id' => 4,
                'role_id' => 4,               
            ],
             [
                'user_id' => 5,
                'role_id' => 4,               
            ],
             [
                'user_id' => 6,
                'role_id' => 4,               
            ],
           
        ];

        $this->loadTable($data);
    }
}
