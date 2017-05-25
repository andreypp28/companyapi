<?php

namespace WA\DataStore\User;

use WA\DataStore\BaseDataStore;

class UserUdlValue extends BaseDataStore
{
	public $timestamps = false;

    protected $table = 'user_udls';
    protected $fillable = ['userId', 'udlValueId'];
}