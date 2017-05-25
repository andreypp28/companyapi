<?php

namespace WA\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use WA\DataStore\BaseDataStore;
use WA\Helpers\Traits\Criteria;
use WA\Http\Requests\Parameters\Filters;
use WA\Http\Requests\Parameters\Sorting;

abstract class AbstractRepository implements RepositoryInterface
{
    use Criteria;

    /**
     * @var \Illuminate\Database\Eloquent\Model|BaseDataStore
     */
    protected $model;

    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * @var Sorting
     */
    protected $sortCriteria = null;

    /**
     * @var Filters
     */
    protected $filterCriteria = null;

    /**
     * AbstractRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->modelClass = get_class($model);
    }

    /**
     * Get paginated resource.
     *
     * @param int  $perPage
     * @param bool $api      false|true
     * @param bool $paginate
     *
     * @return mixed Object as Collection of object information, | Paginator Collection if pagination is true (default)
     */
    public function byPage($paginate = true, $perPage = 25, $api = false)
    {
        // Apply filtering and sorting criteria, if set
        $query = $this->applyCriteria($this->model);

        if (!$paginate) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    /**
     * Get the model by its Id.
     *
     * $param int $id
     *
     * @return object object of model information
     */
    public function byId($id)
    {
        if (is_array($id)) {
            return $this->model->whereIn('id', $id)
                ->get();
        }
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    /**
     * Create a repository.
     *
     * @param array $data to be created
     *
     * @return mixed Object object of created model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Get the model used on the class.
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Delete from the repo by the ID.
     *
     * @param int  $id
     * @param bool $force completely remove for the DB instead of marking it as "deleted"
     *
     * @return int
     */
    public function deleteById($id, $force = false)
    {
        if ($force && !is_array($id)) {
            $instance = $this->byId($id);
            $instance->forceDelete();
        }

        return $this->model->destroy($id);
    }

    /**
     * Update a repository.
     *
     * @param array $data to be updated
     *
     * @return object object of updated repo
     */
    public function update(array $data)
    {
        return $this->model->update($data);
    }

    /**
     * Get the model's transformation.
     */
    public function getTransformer()
    {
        return $this->model->getTransformer();
    }
}
