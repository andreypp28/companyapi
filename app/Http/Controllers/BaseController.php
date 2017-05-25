<?php

namespace WA\Http\Controllers;

use Illuminate\Routing\Controller;
use View;

/**
 * Class BaseController.
 */
class BaseController extends Controller
{
    protected $notifyContainer = 'clean';

    protected $view;

    protected $data = [];

    /**
     * Setup the layout used by the controller.
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }
}
