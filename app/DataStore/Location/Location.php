<?php

namespace WA\DataStore\Location;

use WA\DataStore\BaseDataStore;

/**
 * Class Location.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\User\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Carrier\Carrier[] $carriers
 * @mixin \Eloquent
 */
class Location extends BaseDataStore
{
    protected $table = 'locations';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('WA\DataStore\User\User', 'locationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carriers()
    {
        return $this->hasMany('WA\DataStore\Carrier\Carrier', 'locationId');
    }
}
