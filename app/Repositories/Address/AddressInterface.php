<?php

namespace WA\Repositories\Address;

use WA\Repositories\RepositoryInterface;

/**
 * Interface AddressInterface.
 */
interface AddressInterface extends RepositoryInterface
{
    /**
     * Get Array of all Addresss.
     *
     * @return array of Address
     */
    public function getAllAddress();

    /**
     * Create Address.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update Address.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete Address.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
