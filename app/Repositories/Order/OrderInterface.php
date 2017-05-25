<?php

namespace WA\Repositories\Order;

use WA\Repositories\RepositoryInterface;

/**
 * Interface OrderInterface.
 */
interface OrderInterface extends RepositoryInterface
{
    /**
     * Get Array of all Orders.
     *
     * @return array of Order
     */
    public function getAllOrder();

    /**
     * Create Order.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update Order.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete Order.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
