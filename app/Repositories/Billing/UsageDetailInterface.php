<?php

namespace WA\Repositories\Billing;

use WA\Repositories\RepositoryInterface;

interface UsageDetailInterface extends RepositoryInterface
{
    /**
     * Get the details of an asset by its type.
     *
     * @param $assetId
     * @param $usageType
     *
     * @return mixed
     */
    public function byAssetUsageType($assetId, $usageType);

    /**
     * Get the details of an asset by its type.
     *
     * @param $billingId
     * @param $usageType
     *
     * @return mixed
     */
    public function byBillingUsageType($billingId, $usageType);

    /**
     * Get the usage detaile by its billing Id.
     *
     * @param int $id
     * @param int $reportLineItemId
     *
     * @return object object of billing
     */
    public function byBilling($id, $reportLineItemId);
}
