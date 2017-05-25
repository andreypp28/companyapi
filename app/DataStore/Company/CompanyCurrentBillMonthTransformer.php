<?php

namespace WA\DataStore\Company;

use WA\DataStore\FilterableTransformer;

/**
 * Class CompanyCurrentBillMonthTransformer
 * @package WA\DataStore\Company
 */
class CompanyCurrentBillMonthTransformer extends FilterableTransformer
{
    public function transform(CompanyCurrentBillMonth $currentBillMonth)
    {
        return [
            'bill_month'          => $currentBillMonth->currentBillMonth,
            'carrier'             => $currentBillMonth->carrierId,
            'companyId'           => $currentBillMonth->companyId,
            'id'                  => (int)$currentBillMonth->id,
        ];
    }
}
