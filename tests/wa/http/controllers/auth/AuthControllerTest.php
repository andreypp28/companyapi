<?php

namespace WA\Testing\Auth;

use Laravel\Lumen\Testing\DatabaseMigrations;
use TestCase;
use Cache;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testAccessToken()
    {
    }

    public function testPasswordGrantVerify()
    {
        // CREATE CONSTANTS

        // CREATE CLASS INSTANCE
        $userInterface = app()->make('WA\Repositories\User\UserInterface');
        $authController = new \WA\Http\Controllers\Auth\AuthController($userInterface);

        // CREATE ARGUMENTS
        $email = 'lang.keanu@example.org';
        $password = 'user';

        // CALL THE FUNCTION
        $returnUser = $authController->passwordGrantVerify($email, $password);
        $returnFalse = $authController->passwordGrantVerify($email, 'incorrect');

        // ASSERTS
        $this->assertLessThanOrEqual(3, $returnUser);
        $this->assertFalse($returnFalse);
    }

    public function testSSOGrantVerify()
    {
        // CREATE CONSTANTS

        // CREATE CLASS INSTANCE
        $userInterface = app()->make('WA\Repositories\User\UserInterface');
        $authController = new \WA\Http\Controllers\Auth\AuthController($userInterface);

        // CREATE ARGUMENTS
        $laravelUser['attributes']['id'] = 1;
        Cache::put('saml2user_uuid', $laravelUser, 1);

        // CALL THE FUNCTION
        $returnUuid = $authController->SSOGrantVerify('uuid');
        $returnNoUuid = $authController->SSOGrantVerify('nouuid');

        // ASSERTS
        $this->assertLessThanOrEqual(1, $returnUuid);
        $this->assertFalse($returnNoUuid);
    }
}
