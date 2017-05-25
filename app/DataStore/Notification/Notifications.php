<?php

namespace WA\DataStore\Notification;

use WA\DataStore\MutableDataStore;

/**
 * WA\DataStore\Notification\Notifications.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\User\User[] $users
 * @mixin \Eloquent
 */
class Notifications extends MutableDataStore
{
    protected $table = 'notifications';

    protected $fillable = [
        'from_id',
        'from_type',
        'to_id',
        'to_type',
        'category_id',
        'url',
        'extra',
        'read',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('WA\DataStore\User\User', 'employee_assets', 'assetId', 'userId');
    }
}
