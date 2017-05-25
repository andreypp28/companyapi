<?php

namespace WA\DataStore\User;

use WA\DataStore\BaseDataStore;

class UserAddress extends BaseDataStore
{
    protected $table = 'user_address';
    protected $fillable = ['userId', 'addressId'];
}