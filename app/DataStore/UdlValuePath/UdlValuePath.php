<?php

namespace WA\DataStore\UdlValuePath;

use WA\DataStore\BaseDataStore;

/**
 * WA\DataStore\UdlValuePath\UdlValuePath.
 *
 * @property int $id
 * @property string $udlPath
 * @property int $udlId
 * @property int $externalId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class UdlValuePath extends BaseDataStore
{
    protected $table = 'udl_value_paths';
    protected $fillable = ['udlPath', 'udlId', 'externalId'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->hasMany('WA\DataStore\User\User', 'departmentId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function udl()
    {
        return $this->belongsTo('WA\DataStore\Udl\Udl', 'udlId');
    }
}
