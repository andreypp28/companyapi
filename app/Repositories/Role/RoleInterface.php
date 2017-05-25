<?php

namespace WA\Repositories\Role;

interface RoleInterface
{
    /**
     * Get an array of all the available roles.
     *
     * @return array of roles
     */
    public function getAllRoles();

    /**
     * Get Permissions by the Role ID.
     *
     * @param int $id
     *
     * @return object of the permissions information
     */
    public function getPermissions($id);
}
