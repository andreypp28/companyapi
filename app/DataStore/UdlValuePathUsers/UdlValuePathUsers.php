<?php

namespace WA\DataStore\UdlValuePathUsers;

use WA\DataStore\BaseDataStore;

/**
 * WA\DataStore\UdlValuePathUsers\UdlValuePathUsers.
 *
 * @property int $id
 * @property string $udlValuePathId
 * @property int $creatorId
 * @property string $userEmail
 * @property string $userFirstName
 * @property string $userLastName
 * @property string $userUserId
 */
class UdlValuePathUsers extends BaseDataStore
{
    protected $table = 'udl_value_paths_creators_users';
    protected $fillable = ['udlValuePathId', 'creatorId', 'userEmail', 'userFirstName', 'userLastName', 'userUserId'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->hasMany('WA\DataStore\User\User', 'creatorId');
    }
}
