<?php

namespace WA\Repositories\DeviceVariation;

use WA\Repositories\AbstractRepository;

/**
 * Class EloquentPrice.
 *
 * @package WA\Repositories\Price
 */
class EloquentDeviceVariation extends AbstractRepository implements DeviceVariationInterface
{
    /**
     * Update Price.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $deviceVariation = $this->model->find($data['id']);

        if (!$deviceVariation) {
            return 'notExist';
        }

        if (isset($data['deviceId'])) {
            $deviceVariation->deviceId = $data['deviceId'];
        }
        if (isset($data['carrierId'])) {
            $deviceVariation->carrierId = $data['carrierId'];
        }
        if (isset($data['companyId'])) {
            $deviceVariation->companyId = $data['companyId'];
        }
        if (isset($data['priceRetail'])) {
            $deviceVariation->priceRetail = $data['priceRetail'];
        }
        if (isset($data['price1'])) {
            $deviceVariation->price1 = $data['price1'];
        }
        if (isset($data['price2'])) {
            $deviceVariation->price2 = $data['price2'];
        }
        if (isset($data['priceOwn'])) {
            $deviceVariation->priceOwn = $data['priceOwn'];
        }

        if (!$deviceVariation->save()) {
            return 'notSaved';
        }

        return $deviceVariation;
    }

    /**
     * Get an array of all the available Price.
     *
     * @return array of Price
     */
    public function getAllDeviceVariation()
    {
        $deviceVariations = $this->model->all();

        return $deviceVariations;
    }

    /**
     * Create a new Price.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $deviceVariationData = [
            "deviceId" => isset($data['deviceId']) ? $data['deviceId'] : 0,
            "carrierId" => isset($data['carrierId']) ? $data['carrierId'] : 0,
            "companyId" => isset($data['companyId']) ? $data['companyId'] : 0,
            "priceRetail" => isset($data['priceRetail']) ? $data['priceRetail'] : 0,
            "price1" => isset($data['price1']) ? $data['price1'] : 0,
            "price2" => isset($data['price2']) ? $data['price2'] : 0,
            "priceOwn" => isset($data['priceOwn']) ? $data['priceOwn'] : 0
        ];

        $deviceVariation = $this->model->create($deviceVariationData);

        if (!$deviceVariation) {
            return false;
        }

        return $deviceVariation;
    }

    /**
     * Delete a Price.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true)
    {
        if (!$this->model->find($id)) {
            return false;
        }

        if (!$soft) {
            $this->model->forceDelete($id);
        }

        return $this->model->destroy($id);
    }

    /**
     * Get an array of all the available DeviceVariation.
     *
     * @return Array of DeviceVariation
     */
    public function getDeviceVariationDevices($id)
    {
        $deviceVariations = $this->model->where('deviceId', $id)->get();
        /*->take( $filter['numItems'] )->offset( $filter['numItems'] * ( $filter['page'] - 1 ) );*/
        return $deviceVariations;
    }

    /**
     * Get an array of all the available DeviceVariation.
     *
     * @return Array of DeviceVariation
     */
    public function getDeviceVariationCarriers($id)
    {
        $deviceVariations = $this->model->where('carrierId', $id)->get();
        return $deviceVariations;
    }

    /**
     * Get an array of all the available DeviceVariation.
     *
     * @return Array of DeviceVariation
     */
    public function getDeviceVariationCompanies($id)
    {
        $deviceVariations = $this->model->where('companyId', $id)->get();
        return $deviceVariations;
    }
}
