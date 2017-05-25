<?php

namespace WA\DataStore\Traits;

/**
 * Class BelongsToUser.
 */
trait BelongsToUser
{
    /**
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('WA\DataStore\User\User', 'userId');
    }
}
