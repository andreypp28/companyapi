<?php

namespace WA\Repositories;

interface RepositoryInterface
{
    /**
     * Get paginated resource.
     *
     * @param bool $paginate
     * @param int  $perPage
     *
     * @return object Collection of object information, will return paginated if pagination is true
     */
    public function byPage($paginate = true, $perPage = 25);

    /**
     * Get the model by its Id.
     *
     * @param int $id
     *
     * @return object object of model information
     */
    public function byId($id);

    /**
     * Create a repository.
     *
     * @param array $data to be created
     *
     * @return object object of created repo
     */
    public function create(array $data);

    /**
     * Delete from the repo by the ID.
     *
     * @param int  $id
     * @param bool $force completely remove for the DB instead of marking it as "deleted"
     *
     * @return bool of the effect of the creation
     */
    public function deleteById($id, $force = false);

    /**
     * Get the model used on the class.
     */
    public function getModel();
}
