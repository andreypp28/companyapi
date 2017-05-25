<?php

namespace WA\Repositories\Service;

use WA\Repositories\RepositoryInterface;

/**
 * Interface ServiceInterface.
 */
interface ServiceInterface extends RepositoryInterface
{
    /**
     * Get Array of all Services.
     *
     * @return array of Service
     */
    public function getAllService();

    /**
     * Create Service.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update Service.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete Service.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
