<?php

namespace WA\Repositories\Role;

use Illuminate\Database\Eloquent\Model;
use WA\Repositories\AbstractRepository;

class EloquentRole extends AbstractRepository implements RoleInterface
{
    /**
     * Get an array of all the available roles.
     *
     * @return array of roles
     */
    public function getAllRoles()
    {
        $roles = $this->model->all()->toArray();

        return $roles;
    }

    /**
     * Get Permissions by the Role ID.
     *
     * @param int $id
     *
     * @return object of the permissions information
     */
    public function getPermissions($id)
    {
        return $this->model->where('id', $id)->first()->permissions;
    }
}
