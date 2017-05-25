<?php

namespace WA\Repositories\CompanyCurrentBillMonth;

use Illuminate\Database\Eloquent\Model;
use WA\Repositories\AbstractRepository;

class EloquentCompanyCurrentBillMonth extends AbstractRepository implements  CompanyCurrentBillMonthInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;


    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * CurrentBillMonth by the id.
     *
     * @param int $id
     *
     * @return object object of the CurrentBillMonth information
     */
    public function byId($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get CurrentBillMonths Transformer.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return $this->model->getTransformer();
    }

    /**
     * Get by company id and carrier id
     *
     * @param $companyId
     * @param $carrierId
     * @return mixed
     */
    public function byCompanyIdAndCarrierId($companyId, $carrierId)
    {
        return $this->model->where('companyId', $companyId)->where('carrierId', $carrierId)->first();
    }

    /**
     * Create new entry in current bill month table.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $billMonthData = [
            'companyId' =>  isset($data['companyId']) ? $data['companyId'] : null,
            'carrierId' => isset($data['carrierId']) ? $data['carrierId'] : null,
            'currentBillMonth' => isset($data['billMonth']) ? $data['billMonth'] : null,

        ];

        $currentBillMonth = $this->model->create($billMonthData);

        if (!$currentBillMonth) {
            return false;
        }

        return $currentBillMonth;
    }

    /**
     * Update current bill month for a company/carrier
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $currentBillMonth = $this->model->find($data['id']);

        if (!$currentBillMonth) {
            return false;
        }

        $currentBillMonth->currentBillMonth = isset($data['billMonth']) ? $data['billMonth'] : null;

        if (!$currentBillMonth->save()) {
            return false;
        }

        return $currentBillMonth;
    }




}