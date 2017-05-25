<?php

namespace WA\Repositories\Attribute;

use Illuminate\Database\Eloquent\Model;
use WA\Repositories\Traits\GetArray;

class EloquentAttribute implements AttributeInterface
{
    protected $model;

    use GetArray;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get the attribute information by it's name.
     *
     * @param $name
     *
     * @return object object
     */
    public function byName($name)
    {
        return $this->model->where('name', $name)
            ->first();
    }
}
