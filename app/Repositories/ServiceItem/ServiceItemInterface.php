<?php

namespace WA\Repositories\ServiceItem;

use WA\Repositories\RepositoryInterface;

/**
 * Interface ServiceItemsInterface.
 */
interface ServiceItemInterface extends RepositoryInterface
{
    /**
     * Get Array of all ServiceItems.
     *
     * @return array of ServiceItem
     */
    public function getAllServiceItems();

    /**
     * Create ServiceItem.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update ServiceItem.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete ServiceItem.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
