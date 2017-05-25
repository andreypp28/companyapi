<?php

namespace WA\Repositories\Billing;

use Illuminate\Database\Eloquent\Model;
use WA\Repositories\AbstractRepository;
use WA\Repositories\Asset\AssetInterface;

class EloquentBillingDetail extends AbstractRepository implements BillingDetailInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var AssetInterface
     */
    protected $asset;

    public function __construct(Model $model, AssetInterface $asset)
    {
        $this->model = $model;
        $this->asset = $asset;
    }

    /**
     * Get the detail by the asset id.
     *
     * @param $assetId
     * @param $billMonth
     *
     * @return object object of the details
     */
    public function byAssetId($assetId, $billMonth = null)
    {
        return $this->model->where('assetId', $assetId)->first();
    }

    /**
     * Get the billing detail of an asset.
     *
     * @param $assetId
     * @param $billMonth
     *
     * @return mixed
     */
    public function getAssetBilling($assetId, $billMonth = null)
    {
        $billingModel = $this->model->where('assetId', $assetId);

        if (!is_null($billMonth)) {
            $billingModel->whereHas('accountSummary', function ($q) use ($billMonth) {
                $q->where('billMonth', $billMonth);
            });
        }

        return $billingModel;
    }

    /**
     * Get the billing details by the billing.
     *
     * @param $billingId
     *
     * @return object object of billing information
     */
    public function byBilling($billingId)
    {
        $model = $this->model->where('billingId', $billingId);

        if (is_null($model)) {
            return;
        }

        return $model;
    }
}
