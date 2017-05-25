<?php

namespace WA\Repositories\App;

use WA\Repositories\RepositoryInterface;

/**
 * Interface AppInterface.
 */
interface AppInterface extends RepositoryInterface
{
    /**
     * Get Array of all Apps.
     *
     * @return array of App
     */
    public function getAllApp();

    /**
     * Create App.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update App.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete App.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
