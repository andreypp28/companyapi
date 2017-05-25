<?php

namespace WA\Auth;

interface UserAuthInterface
{
    /**
     * Check if the logged in user can add users to the system.
     *
     * @return bool
     */
    public function canAddUsers();

    /**
     * Check if the logged in user can edit other users in the system.
     *
     * @return bool
     */
    public function canEditUsers();
}
