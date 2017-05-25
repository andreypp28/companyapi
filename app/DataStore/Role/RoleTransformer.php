<?php

namespace WA\DataStore\Role;

use WA\DataStore\FilterableTransformer;

/**
 * Class RoleTransformer.
 */
class RoleTransformer extends FilterableTransformer
{
    protected $availableIncludes = [
        'permissions',
        'users',
    ];

    /**
     * @param Role $role
     *
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'id'   => (int)$role->id,
            'name' => $role->display_name
        ];
    }
}
