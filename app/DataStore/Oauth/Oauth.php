<?php

namespace WA\DataStore\Oauth;

use WA\DataStore\BaseDataStore;

/**
 * Class App.
 */
class Oauth extends BaseDataStore
{
    protected $table = 'oauth_clients';

    protected $fillable = [
            'user_Id',
            'name',
            'secret',
            'redirect',
            'personal_access_client',
            'password_client',
            'revoked'];
}
