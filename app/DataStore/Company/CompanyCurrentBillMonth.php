<?php

namespace WA\DataStore\Company;

use WA\DataStore\BaseDataStore;

/**
 * WA\DataStore\Company\CompanyCurrentBillMonth.
 *
 * @property int $id
 * @property int $companyId
 * @property id $carrierId
 * @property \Carbon\Carbon $currentBillMonth
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Company\CompanyCurrentBillMonth whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Company\CompanyCurrentBillMonth whereCompanyId($value)
 */
class CompanyCurrentBillMonth extends BaseDataStore
{
    protected $table = 'company_current_bill_months';

    protected $fillable = ['id', 'companyId', 'carrierId', 'currentBillMonth'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companies()
    {
        return $this->belongsTo('WA\DataStore\Company\Company', 'companyId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carriers()
    {
        return $this->belongsTo('WA\DataStore\Carrier\Carrier', 'carrierId');
    }

    /**
     * @return CompanyCurrentBillMonthTransformer
     */
    public function getTransformer()
    {
        return new CompanyCurrentBillMonthTransformer();
    }
}