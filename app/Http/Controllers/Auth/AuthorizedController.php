<?php

namespace WA\Http\Controllers\Auth;

use Auth;
use Dingo\Api\Routing\Helpers as ApiHelpers;
use Illuminate\Support\Facades\Session;
use WA\Http\Controllers\BaseController;

/**
 * Class AuthorizedController.
 */
class AuthorizedController extends BaseController
{
    use ApiHelpers;

    /**
     * @var Authenticated user
     */
    protected $user;

    /**
     * @var Company
     */
    protected $currentCompany;

    /**
     * @var device
     */
    protected $device;

    /**
     * AuthorizedController constructor.
     */
    public function __construct()
    {
        /*
        $this->currentCompany = Session::get('clean.company');
        $this->user = Auth::user();
        */
    }
}
