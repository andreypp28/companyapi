<?php

namespace WA\DataStore\Company;

use WA\DataStore\BaseDataStore;

/**
 * WA\DataStore\Company\CompanySaml2.
 *
 * @mixin \Eloquent
 */
class CompanySaml2 extends BaseDataStore
{
    protected $table = 'company_saml2';

    protected $fillable = ['entityId', 'singleSignOnServiceUrl', 'singleSignOnServiceBinding', 'singleLogoutServiceUrl', 'singleLogoutServiceBinding', 'companyId', 'emailAttribute', 'firstNameAttribute', 'lastNameAttribute'];
}
