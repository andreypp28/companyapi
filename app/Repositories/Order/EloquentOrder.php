<?php

namespace WA\Repositories\Order;

use WA\Repositories\AbstractRepository;

/**
 * Class EloquentOrder.
 */
class EloquentOrder extends AbstractRepository implements OrderInterface
{
    /**
     * Update Order.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $order = $this->model->find($data['id']);

        if (!$order) {
            return 'notExist';
        }

        if (isset($data['status'])) {
            $order->status = $data['status'];
        }
        if (isset($data['userId'])) {
            $order->userId = $data['userId'];
        }
        if (isset($data['packageId'])) {
            $order->packageId = $data['packageId'];
        }
        if (isset($data['deviceId'])) {
            $order->deviceId = $data['deviceId'];
        }
        if (isset($data['serviceId'])) {
            $order->serviceId = $data['serviceId'];
        }
        if (isset($data['addressId'])) {
            $order->addressId = $data['addressId'];
        }

        if (!$order->save()) {
            return 'notSaved';
        }

        return $order;
    }

    /**
     * Get an array of all the available order.
     *
     * @return array of order
     */
    public function getAllOrder()
    {
        $order = $this->model->all();

        return $order;
    }

    /**
     * Create a new order.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data)
    {
        $orderData = [
            'status'        => isset($data['status'])       ? $data['status']       : null,
            'userId'        => isset($data['userId'])       ? $data['userId']       : null,
            'packageId'     => isset($data['packageId'])    ? $data['packageId']    : null,
            'deviceId'      => isset($data['deviceId'])     ? $data['deviceId']     : null,
            'serviceId'     => isset($data['serviceId'])    ? $data['serviceId']    : null,
            'addressId'     => isset($data['addressId'])    ? $data['addressId']    : null,
        ];

        $order = $this->model->create($orderData);

        if (!$order) {
            return false;
        }

        return $order;
    }

    /**
     * Delete a order.
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
}
