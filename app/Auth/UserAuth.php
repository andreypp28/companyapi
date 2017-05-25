<?php

namespace WA\Auth;

use Auth;
use Illuminate\Support\Facades\Session;

class UserAuth implements \WA\Auth\UserAuthInterface
{
    protected $user;

    protected $currentCompany;

    public function __construct()
    {
        $this->currentCompany = Session::get('clean.company');
        $this->user = Auth::user();
    }

    /**
     * Check if the logged in user can add users to the system.
     *
     * @return bool
     */
    public function canAddUsers()
    {
        return $this->user->can('add_users');
    }

    /**
     * Check if the logged in user can edit other users in the system.
     *
     * @return bool
     */
    public function canEditUsers()
    {
        return $this->user->can('edit_users');
    }
}
