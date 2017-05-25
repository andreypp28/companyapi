<?php

namespace WA\Repositories\Image;

use WA\Repositories\RepositoryInterface;

/**
 * Interface ImageInterface.
 */
interface ImageInterface extends RepositoryInterface
{
    /**
     * Get Array of all Images.
     *
     * @return array of Image
     */
    public function getAllImage();

    /**
     * Create Image.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update Image.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);

    /**
     * Delete Image.
     *
     * @param int  $id
     * @param bool $soft true soft deletes
     *
     * @return bool
     */
    public function delete($id, $soft = true);
}
