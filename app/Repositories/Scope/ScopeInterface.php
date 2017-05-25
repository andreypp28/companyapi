<?php

namespace WA\Repositories\Scope;

interface ScopeInterface
{    
     /**
     * Get an array of all the available scopes.
     *
     * @return array of scopes
     */
    public function getAllScopes();
    /**
     * Get the Scopes by the Permission ID.
     *
     * @param int $id
     *
     * @return object of the Scopes information
     */
    public function byPermission($id);
}
