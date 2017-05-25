<?php

namespace WA\Repositories\Permission;

use WA\Repositories\AbstractRepository;
use WA\Repositories\Role\RoleInterface;
use WA\Repositories\Permission\PermissionInterface;

class EloquentPermission extends AbstractRepository implements PermissionInterface
{
    protected $role;

    /**
     * Get the Permissions by the Role ID.
     *
     * @param int $id
     *
     * @return object of the permissions information
     */
    public function byRole($id)
    {
        $role = $this->role->byId($id);

        return $role->permissions;
    }

 /**
     * Get Permissions by the Scope ID.
     *
     * @param int $id
     *
     * @return object of the permissions information
     */
    public function getScope($id)
    {
        return $this->model->where('id', $id)->first()->scopes;
    }
    
    
}
