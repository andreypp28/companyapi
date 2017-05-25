<?php

namespace WA\Repositories\Category;

use WA\Repositories\RepositoryInterface;

/**
 * Interface CategoryDeviceInterface.
 */
interface CategoryAppsInterface extends RepositoryInterface
{
    /**
     * Get Array of all Categories.
     *
     * @return array of Category
     */
    public function getAllCategories();

    /**
     * Create Category.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update Category.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete Category.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
