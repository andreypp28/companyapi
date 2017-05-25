<?php

namespace WA\Repositories\Modification;

use WA\Repositories\RepositoryInterface;

/**
 * Interface OrderInterface.
 */
interface ModificationInterface extends RepositoryInterface
{
    /**
     * Get Array of all Modifications.
     *
     * @return array of Order
     */
    public function getAllModification();

    /**
     * Create Modification.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update Modification.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete Modification.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
