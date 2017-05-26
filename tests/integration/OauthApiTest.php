<?php

namespace WA\Testing\Auth;

use Laravel\Lumen\Testing\DatabaseMigrations;
use WA\DataStore\User\User;
use Laravel\Passport\Bridge\Scope;
use WA\DataStore\Scope\Scope as ScopeModel;
use Laravel\Passport\Passport;
use TestCase;

class OauthApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testApiOauthAccessToken()
    {
        $grantType = 'password';
        $password = 'user';
        $scope = factory(\WA\DataStore\Scope\Scope::class)->create(['name' => 'get', 'display_name'=>'get']);
        
        $user = factory(\WA\DataStore\User\User::class)->create([
            'email' => 'email@email.com',
            'password' => '$2y$10$oc9QZeaYYAd.8BPGmXGaFu9cAycKTcBu7LRzmT2J231F0BzKwpxj6'
        ]);

        $role = factory(\WA\DataStore\Role\Role::class)->create();
        $permission1 = factory(\WA\DataStore\Permission\Permission::class)->create();
        $permission2 = factory(\WA\DataStore\Permission\Permission::class)->create();
        $user->roles()->sync([$role->id]);
        $role->perms()->sync([$permission1->id,$permission2->id]);
        $scope->permissions()->sync([$permission1->id,$permission2->id]);
        $scp = $scope->name;
        
        $oauth = factory(\WA\DataStore\Oauth\Oauth::class)->create([
            'user_Id' => null,
            'name' => 'Password Grant Client',
            'secret' => 'ab9QdKGBXZmZn50aPlf4bLlJtC4BJJNC0M99i7B7',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
        ]);

        // Setup TokensCan as in AuthSericeProvider, as it is not properly executed on app bootstrap during the test

        $scopes = ScopeModel::all();
            
        $listScope = array();
        foreach ($scopes as $scop){
            $listScope[$scop->getAttributes()['name']] = $scop->getAttributes()['description'];
        }

        Passport::tokensCan($listScope);
       
        $body = [
            'grant_type' => $grantType,
            'username' => $user->email,
            'password' => $password,
            'client_id' => $oauth->id,
            'client_secret' => $oauth->secret,
            'scope' => $scp
        ];

        $call = $this->call('POST', 'oauth/token', $body, [], [], [], true );
        $array = (array)json_decode($call->getContent());
        
        $this->assertArrayHasKey('user_id', $array);
        $this->assertArrayHasKey('token_type', $array);
        $this->assertArrayHasKey('expires_in', $array);
        $this->assertArrayHasKey('access_token', $array);
        $this->assertArrayHasKey('refresh_token', $array);

        $this->assertEquals($array['user_id'], '1');
        $this->assertEquals($array['token_type'], 'Bearer');
        $this->assertEquals(strlen($array['access_token']),1078);
        $this->assertEquals(strlen($array['refresh_token']), 684);
    }
}