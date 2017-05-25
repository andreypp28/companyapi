<?php
/**
 * Blameable auditing support for Laravel's Eloquent ORM
 *
 * @author Ross Masters <ross@rossmasters.com>
 * @copyright Ross Masters 2013
 * @license MIT
 */

use Illuminate\Support\Facades\Auth;

return array(
    'users' => array(
        /**
         * Retrieve the authenticated user's ID
         * @return int|null User ID, or null if not authenticated
         */
        'active_user' => function() {
            return Auth::check() ? Auth::user() : null;
        },

        /**
          * Class name of the user object to relate to
         * @var string
         */
        'classname' => 'WA\DataStore\User\User',
    ),
    'default_fields' => [
        'created' => 'created_by_id',
        'updated' => 'updated_by_id',
        'deleted' => 'deleted_by_id',
    ]
);
