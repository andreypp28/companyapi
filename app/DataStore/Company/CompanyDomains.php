<?php

namespace WA\DataStore\Company;

use WA\DataStore\BaseDataStore;

/**
 * WA\DataStore\Company\CompanySaml2.
 *
 * @mixin \Eloquent
 */
class CompanyDomains extends BaseDataStore
{
    protected $table = 'company_domains';

    protected $fillable = ['domain', 'active', 'companyId'];
}
