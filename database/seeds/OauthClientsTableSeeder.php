<?php

/**
 * OauthClientsTableSeeder - Insert password grant token and personal acess client.
 *
 * @author   Marcio Rezende
 */
class OauthClientsTableSeeder extends BaseTableSeeder
{
    protected $table = 'oauth_clients';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                
                'user_Id' => null,
                'name' => 'Personal Access Client',
                'secret' => 'Abk5iAKZiXVxt5sQdvmHzIzhjpFr7TwOiGhsPYsP',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
                

            ],
     
            [
                
                'user_Id' => null,
                'name' => 'Password Grant Client',
                'secret' => 'ab9QdKGBXZmZn50aPlf4bLlJtC4BJJNC0M99i7B7',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                
            ],
            
        ];

        $this->loadTable($data);
    }
}
