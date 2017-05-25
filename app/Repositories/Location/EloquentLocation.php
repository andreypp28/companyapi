<?php

namespace WA\Repositories\Location;

use WA\Repositories\AbstractRepository;

class EloquentLocation extends AbstractRepository implements LocationInterface
{
    /**
     * Get location details by name.
     *
     * @param $name
     *
     * @return mixed
     */
    public function byName($name)
    {
        return $this->model->where('name', $name)->first();
    }
}
