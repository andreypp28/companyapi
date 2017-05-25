<?php

namespace WA\Repositories\Image;

use WA\Repositories\AbstractRepository;

/**
 * Class EloquentImage.
 */
class EloquentImage extends AbstractRepository implements ImageInterface
{
    /**
     * Update Image.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        return $this->model->update($data);
    }

    /**
     * Get an array of all the available Image.
     *
     * @return array of Image
     */
    public function getAllImage()
    {
        $image = $this->model->all();

        return $image;
    }

    /**
     * Delete a Image.
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
