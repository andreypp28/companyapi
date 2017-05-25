<?php

/**
 * Created by PhpStorm.
 * User: dele
 * Date: 4/16/2015
 * Time: 8:15 PM.
 */

namespace WA\DataStore\User;

use WA\DataStore\BaseDataStore;

/**
 * Class Password Reminders.
 *
 * @mixin \Eloquent
 */
class PasswordResets extends BaseDataStore
{
    protected $table = 'password_resets';
}
