<?php

namespace WA\DataStore\Permission;

use WA\DataStore\FilterableTransformer;

/**
 * Class PermissionTransformer.
 */
class PermissionTransformer extends FilterableTransformer
{

    protected $availableIncludes = [
        'scopes',
        'roles',
    ];

    /**
     * @param Permission $permission
     *
     * @return array
     */
    public function transform(Permission $permission)
    {
        return [
            'id'   => (int)$permission->id,
            'name' => $permission->display_name,
           
        ];
    }
}
