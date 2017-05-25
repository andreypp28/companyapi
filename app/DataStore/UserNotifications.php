<?php

namespace WA\DataStore;

/**
 * Class UserNotification.
 *
 * @property-read \WA\DataStore\User\User $users
 * @mixin \Eloquent
 */
class UserNotifications extends BaseDataStore
{
    protected $table = 'employee_notifications';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('WA\DataStore\User\User', 'userId');
    }
}
