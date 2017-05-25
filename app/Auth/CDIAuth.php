<?php

namespace WA\Auth;

use Auth;
use Illuminate\Support\Facades\Session;

class CDIAuth implements CDIAuthInterface
{
    protected $user;

    protected $currentCompany;

    public function __construct()
    {
        $this->currentCompany = Session::get('clean.company');
        $this->user = Auth::user();
    }

    /**
     * Check if the logged in user can access CDI related functions.
     *
     * @return bool
     */
    public function getCDIAuthorization()
    {
        $cdi_permissions = ['cdi_process'];
        foreach ($cdi_permissions as $permission) {
            if (!$this->user->can($permission)) {
                return false;
            }
        }

        return true;
    }
}
