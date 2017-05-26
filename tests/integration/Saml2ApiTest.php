<?php

namespace WA\Testing\Auth;

use Laravel\Lumen\Testing\DatabaseMigrations;
use WA\Auth\Login;
use TestCase;
use Cache;
use Log;

class Saml2ApiTest extends TestCase
{
    use DatabaseMigrations;

    /*
    return response()
        ->json(['error' => 'Invalid Email', 'message' => 'Please, enter a valid Email Address.'])
        ->setStatusCode($this->status_codes['conflict']);
    */
    public function testApiDoSSOInvalidEmail()
    {
        // CREATE ARGUMENTS
        $emailInvalidEmail = 'dev@a';

        // CALL THE API ROUTE + ASSERTS
        $returnNoValid = $this->json('GET', 'doSSO/'.$emailInvalidEmail)->seeJson([
            'error' => 'Invalid Email',
            'message' => 'Please, enter a valid Email Address.',
        ]);
    }

    /*
    return response()
            ->json(['error' => 'Company Not Found', 'message' => 'Please, contact with the administrator of your company.'])
            ->setStatusCode($this->status_codes['conflict']);
    */
    public function testApiDoSSOCompanyNotExist() 
    {
        // CREATE ARGUMENTS
        $emailCompanyNotExist = 'dev@testing.com';

        // CALL THE API ROUTE + ASSERTS
        $returnNoValid = $this->json('GET', 'doSSO/'.$emailCompanyNotExist)->seeJson([
            'error' => 'Company Not Found',
            'message' => 'Please, contact with the administrator of your company.',
        ]);
    }

    /*
    return response()
        ->json(['error' => 'User Not Found, Register Required', 'message' => 'Please, register a new user.'])
        ->setStatusCode($this->status_codes['conflict']);
    */
    public function testApiDoSSOUserNotExist()
    {
        $emailUserNotExist = 'dev@testing.com';
        $redirectToUrl = 'http://google.es';

        // CREATE COMPANY and COMPANY DOMAIN
        $companyId = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $companyDomains = factory(\WA\DataStore\Company\CompanyDomains::class)->create(['companyId' => $companyId, 'domain' => 'testing.com']);

        // CALL THE API ROUTE + ASSERTS
        $returnPassword = $this->json('GET', 'doSSO/'.$emailUserNotExist.'?redirectToUrl='.$redirectToUrl)->seeJson([
            'error' => 'User Not Found, Register Required',
            'message' => 'Please, register a new user.',
        ]);
    }

    /*
    return response()
        ->json(['error' => 'User Found, Password Required', 'message' => 'Please, enter your password.'])
        ->setStatusCode($this->status_codes['conflict']);
    */
    public function testApiDoSSOEmailPassword()
    {
        $email = 'dev@withpassword.com';
        $redirectToUrl = 'http://google.es';

        // CREATE COMPANY and COMPANY DOMAIN
        $company = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $companyDomains = factory(\WA\DataStore\Company\CompanyDomains::class)->create(['companyId' => $company, 'domain' => 'withpassword.com']);
        // CREATE USER
        $user = factory(\WA\DataStore\User\User::class)->create(['email' => $email, 'companyId' => $company])->email;

        // CALL THE API ROUTE + ASSERTS
        $returnPassword = $this->json('GET', 'doSSO/'.$user.'?redirectToUrl='.$redirectToUrl)->seeJson([
            'error' => 'User Found, Password Required',
            'message' => 'Please, enter your password.',
        ]);
    }

    /*
    return response()
        ->json(['error' => 'URL Not Found', 'message' => 'Url to redirect not found.'])
        ->setStatusCode($this->status_codes['conflict']);
    */
    public function testApiDoSSOEmailMicrosoftFail()
    {
        // CREATE ARGUMENTS
        $emailMicrosoft = 'dev@wirelessanalytics.com';

        $company = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $companyDomains = factory(\WA\DataStore\Company\CompanyDomains::class)->create(['companyId' => $company]);
        $companySaml2 = factory(\WA\DataStore\Company\CompanySaml2::class)->create(['companyId' => $company]);
        $user = factory(\WA\DataStore\User\User::class)->create(['email' => $emailMicrosoft, 'companyId' => $company]);

        // CALL THE API ROUTE + ASSERTS
        $returnMicrosoft = $this->call('GET', 'doSSO/'.$emailMicrosoft, array(), array(), array(), array(), array());
        $returnMicrosoftArray = json_decode($returnMicrosoft->content(), true);

        $this->assertArrayHasKey('error', $returnMicrosoftArray);
        $this->assertArrayHasKey('message', $returnMicrosoftArray);
        $this->assertStringStartsWith('URL Not Found', $returnMicrosoftArray['error']);
        $this->assertStringStartsWith('Url to redirect not found.', $returnMicrosoftArray['message']);
    }

    public function testApiDoSSOEmailMicrosoftSaml2()
    {
        // CREATE ARGUMENTS
        $emailMicrosoft = 'dev@wirelessanalytics.com';
        $redirectToUrl = 'http://google.es';

        $company = factory(\WA\DataStore\Company\Company::class)->create()->id;
        $companyDomains = factory(\WA\DataStore\Company\CompanyDomains::class)->create(['companyId' => $company]);
        $companySaml2 = factory(\WA\DataStore\Company\CompanySaml2::class)->create(['companyId' => $company]);
        $user = factory(\WA\DataStore\User\User::class)->create(['email' => $emailMicrosoft, 'companyId' => $company]);

        // CALL THE API ROUTE + ASSERTS
        $returnMicrosoft = $this->call('GET', 'doSSO/'.$emailMicrosoft.'?redirectToUrl='.$redirectToUrl, array(), array(), array(), array(), array());
        $returnMicrosoftArray = json_decode($returnMicrosoft->content(), true);
        Log::debug("I cannot replicate the issue related to the phpunit test fail.");
        Log::debug("Return Microsoft Array : ".print_r($returnMicrosoftArray, true));
        $this->assertArrayHasKey('data', $returnMicrosoftArray);
        $this->assertArrayHasKey('redirectUrl', $returnMicrosoftArray['data']);
        $this->assertStringStartsWith('https://login.microsoftonline.com', $returnMicrosoftArray['data']['redirectUrl']);
    }

    public function testApiDoSSOLoginUuid()
    {
        // CREATE ARGUMENTS ERROR
        $uuid = 'siriondevelopers';

        // CALL THE API ROUTE WITHOUT LARAVEL USER -> ERROR
        $returnRegister = $this->json('GET', 'doSSO/login/'.$uuid)->seeJson([
            'error' => 'Required User',
            'message' => 'Please, user is not available now, try again later.',
        ]);

        // CREATE ARGUMENTS SUCCESS
        $laravelUser['attributes']['id'] = 1;
        Cache::put('saml2user_'.$uuid, $laravelUser, 1);

        // CALL THE API ROUTE + ASSERTS
        $returnRegister = $this->json('GET', 'doSSO/login/'.$uuid)->seeJson([
            'success' => 'User Successfully Logged',
            'uuid' => $uuid,
        ]);
    }
}
