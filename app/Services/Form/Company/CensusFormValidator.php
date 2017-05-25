<?php

namespace WA\Services\Form\Company;

use WA\Services\Validation\AbstractLaravelValidator;

/**
 * Class BulkUserFormValidator.
 */
class CensusFormValidator extends AbstractLaravelValidator
{
    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules = [
        'file' => 'required|mimes:xlsx,csv,xls',
        'companyId' => 'required',
    ];

    /**
     * Custom validation messages.
     *
     * @var array
     */
    protected $messages = [
        'companyId.required' => 'You must select a company to continue',
        'file.mimes' => 'Please upload a supported file (xls, xlsx or csv)',
        'file.required' => 'You should a file of (xls, xlsx or csv)',
    ];
}
