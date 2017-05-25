<?php

namespace WA\Repositories\JobStatus;

use Illuminate\Database\Eloquent\Model;

class EloquentJobStatus implements JobStatusInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get the job id by it's name.
     *
     * @param $name
     *
     * @return int id | null
     */
    public function idByName($name)
    {
        $model = $this->model->where('name', $name);

        if (!$model) {
            return;
        }

        return $model->pluck('id');
    }

    /**
     * Get the status name by its ID.
     *
     * @param $id
     *
     * @return string $name | null
     */
    public function nameById($id)
    {
        $model = $this->model->where('id', $id);

        if (!$model) {
            return;
        }

        return $model->pluck('name');
    }
}
