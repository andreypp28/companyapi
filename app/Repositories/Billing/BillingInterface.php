<?php

namespace WA\Repositories\Billing;

use WA\Repositories\RepositoryInterface;
use WA\Services\Money\Money;

interface BillingInterface extends RepositoryInterface
{
    /**
     * Get the total of a particular field.
     *
     * @param $column
     * @param $type
     * @param $billMonth
     *
     * @return Money $total
     */
    public function getTotalOf($column, $type, $billMonth);

    /**
     * Fot a set company, get the latest billMonth.
     *
     * @param $companyId
     *
     * @return mixed
     */
    public function getLastBillMonth($companyId);

    /**
     * Get the transformer on this system.
     *
     * @param $type string
     *
     * @return mixed
     */
    public function getTransformer($type);
}
