<?php

namespace WA\Repositories\Scope;

use WA\Repositories\AbstractRepository;
use WA\Repositories\Permission\PermissionInterface;



class EloquentScope extends AbstractRepository implements ScopeInterface
{
    protected $permission;
    
    /**
     * Get the Scopes by the Permission ID.
     *
     * @param int $id
     *
     * @return object of the permissions information
     */
    public function byPermission($id)
    {
        $permission = $this->permission->byId($id);

        return $permission->scopes;
    }
    /**
     * Get an array of all the available scopes.
     *
     * @return array of scopes
     */
    public function getAllScopes()
    {
        $scopes = $this->model->all()->toArray();

        return $scopes;
    }
    
    
}
