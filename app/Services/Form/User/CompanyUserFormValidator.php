<?php

namespace WA\Services\Form\User;

use WA\Services\Validation\AbstractLaravelValidator;

/**
 * Class UserFormValidator.
 */
class CompanyUserFormValidator extends AbstractLaravelValidator
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'email' => 'required|email',
    ];

    // verify that the email is in of the acceptable domains
}
