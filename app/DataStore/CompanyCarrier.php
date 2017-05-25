<?php

namespace WA\DataStore;

use WA\DataStore\Traits\BelongsToCarrier;
use WA\DataStore\Traits\BelongsToCompany;

/**
 * Class CompanyCarrier.
 *
 * @property-read \WA\DataStore\Carrier\Carrier $carrier
 * @property-read \WA\DataStore\Company\Company $company
 * @mixin \Eloquent
 */
class CompanyCarrier extends BaseDataStore
{
    public $timestamps = false;
    protected $table = 'companies_carriers';
    protected $fillable = [
        'carrierId',
        'companyId',
        'name',
        'billingAccountNumber',
        'parentAccountNumber',
        'accountName',
        'carrierDiscount',
        'active',
    ];

    use BelongsToCarrier, BelongsToCompany;
}
