<?php

namespace WA\Repositories\Billing;

use WA\Repositories\RepositoryInterface;

interface BillingDetailInterface extends RepositoryInterface
{
    /**
     * Get the detail by the asset id.
     *
     * @param $assetId
     * @param $billMonth
     *
     * @return object object of the details
     */
    public function byAssetId($assetId, $billMonth = null);

    /**
     * Get the billing detail of an asset.
     *
     * @param $assetId
     * @param $billMonth
     *
     * @return mixed
     */
    public function getAssetBilling($assetId, $billMonth = null);

    /**
     * Get the billing details by the billing.
     *
     * @param $billingId
     *
     * @return object object of billing information
     */
    public function byBilling($billingId);
}
