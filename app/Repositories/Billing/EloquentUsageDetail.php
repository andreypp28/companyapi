<?php

namespace WA\Repositories\Billing;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use WA\Repositories\AbstractRepository;

class EloquentUsageDetail extends AbstractRepository implements UsageDetailInterface
{
    /**
     * Get the details of an asset by its type.
     *
     * @param $assetId
     * @param $usageType
     *
     * @return mixed
     */
    public function byAssetUsageType($assetId, $usageType)
    {
        $model = $this->model;

        if (!$assetId || !$usageType) {
            throw new InvalidArgumentException('Provide an asset id of usage type');
        }

        $model = $model->where('assetId', $assetId);

        $model = $model->whereHas('reportLineItem', function ($q) use ($usageType) {
            $q->where('name', $usageType);
        });

        return $model;
    }

    /**
     * Get the usage details by its billing Id.
     *
     * @param int $id
     * @param int $reportLineItemId
     *
     * @return object object of billing
     */
    public function byBilling($id, $reportLineItemId)
    {
        $model = $this->model;

        if (!$id || !$reportLineItemId) {
            throw new InvalidArgumentException('Provide an asset id of usage type');
        }

        $model = $model->where('billingId', $id)
            ->where('reportLineItemId', $reportLineItemId);

        return $model;
    }

    /**
     * Get the details of an asset by its type.
     *
     * @param $billingId
     * @param $usageType
     *
     * @return mixed
     */
    public function byBillingUsageType($billingId, $usageType)
    {
        $model = $this->model;

        if (!$billingId || !$usageType) {
            throw new InvalidArgumentException('Provide an asset id of usage type');
        }

        $model = $model->where('assetId', $billingId);

        $model = $model->whereHas('reportLineItem', function ($q) use ($usageType) {
            $q->where('name', $usageType);
        });

        return $model;
    }
}
