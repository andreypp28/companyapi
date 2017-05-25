<?php

namespace WA\Repositories\Traits;

/**
 * Common method for retrieving a flat array collection of models.
 */
trait GetArray
{
    /**
     * Get a quick-access array of key -> ID.
     *
     * @param string $key
     *
     * @return array
     */
    public function getArray($key = 'name')
    {
        $array = [];
        $collection = $this->model->get(['id', $key])->toArray();
        foreach ($collection as $do) {
            $array[$do['name']] = $do['id'];
        }

        return $array;
    }
}
