<?php

namespace WA\Repositories\Permission;

interface PermissionInterface
{    
     /**
     * Get the Permissions by the Scope ID.
     *
     * @param int $id
     *
     * @return object of the permissions information
     */
    public function getScope($id);
    /**
     * Get the Permissions by the Role ID.
     *
     * @param int $id
     *
     * @return object of the permissions information
     */
    public function byRole($id);
}
