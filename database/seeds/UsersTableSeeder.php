<?php

class UsersTableSeeder extends BaseTableSeeder
{
    protected $table = 'users';

    public function run()
    {
        $this->deleteTable();

        $dataUserUdl1 = [
            'identification' => uniqid('WA-'),
            'uuid' => '123456789123',
            'email' => 'data1@wirelessanalytics.com',
            'supervisorEmail' => 'admin@siriondev.com',
            'password' => bcrypt('user'),
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed' => 1,
            'firstName' => 'Sirion',
            'lastName' => 'Developers',
            'username' => 'sirion',
            'defaultLang' => 'en',
            'supervisorId' => null,
            'notify' => 0,
            'approverId' => 3,
            'defaultLocationId' => 'location',
            'companyId' => 1,
        ];

        $this->loadTable($dataUserUdl1);

        $dataUserUdl2 = [
            'identification' => uniqid('WA-'),
            'uuid' => '123456789456',
            'email' => 'data2@wirelessanalytics.com',
            'supervisorEmail' => 'admin@siriondev.com',
            'password' => bcrypt('user'),
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed' => 1,
            'firstName' => 'FirstName2',
            'lastName' => 'LastName2',
            'username' => 'Username2',
            'defaultLang' => 'en',
            'supervisorId' => 1,
            'notify' => 0,
            'approverId' => 3,
            'defaultLocationId' => 'location',
            'companyId' => 2,
        ];

        $this->loadTable($dataUserUdl2);

        $dataUserUdl3 = [
            'identification' => uniqid('WA-'),
            'uuid' => '123456789789',
            'email' => 'data3@wirelessanalytics.com',
            'supervisorEmail' => 'admin@siriondev.com',
            'password' => bcrypt('user'),
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed' => 1,
            'firstName' => 'FirstName3',
            'lastName' => 'LastName3',
            'username' => 'Username3',
            'defaultLang' => 'en',
            'supervisorId' => 1,
            'notify' => 0,
            'approverId' => 3,
            'defaultLocationId' => 'location',
            'companyId' => 3,
        ];

        $this->loadTable($dataUserUdl3);

        $dataUserLogin = [
            'identification' => uniqid('WA-'),
            'uuid' => '123456789',
            'email' => 'dev@wirelessanalytics.com',
            'supervisorEmail' => 'admin@siriondev.com',
            'password' => bcrypt('user'),
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed' => 1,
            'firstName' => 'Sirion',
            'lastName' => 'Developers',
            'username' => 'sirion',
            'defaultLang' => 'en',
            'supervisorId' => 1,
            'notify' => 0,
            'approverId' => 3,
            'defaultLocationId' => 'location',
            'companyId' => 5,
        ];

        $this->loadTable($dataUserLogin);

        $dataUserLogin = [
            'identification' => uniqid('Test-'),
            'uuid' => 'testinguuid',
            'email' => 'email@testing.com',
            'supervisorEmail' => null,
            'password' => bcrypt('user'),
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed' => 1,
            'firstName' => 'Testing',
            'lastName' => 'Developers',
            'username' => 'testing',
            'defaultLang' => 'en',
            'supervisorId' => 1,
            'notify' => 0,
            'approverId' => 3,
            'defaultLocationId' => 'location',
            'companyId' => 20,
        ];

        $this->loadTable($dataUserLogin);

        factory(\WA\DataStore\User\User::class, 20)->create();
    }
}
