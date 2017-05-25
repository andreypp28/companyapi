<?php

namespace WA\DataStore\UdlValue;

use WA\DataStore\BaseDataStore;

/**
 * WA\DataStore\UdlValue\UdlValue.
 *
 * @property int $id
 * @property string $name
 * @property int $udlId
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\User\User[] $e mployees
 * @property-read \WA\DataStore\Udl\Udl $udl
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UdlValue\UdlValue whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UdlValue\UdlValue whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UdlValue\UdlValue whereUdlId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UdlValue\UdlValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\UdlValue\UdlValue whereUpdatedAt($value)
 */
class UdlValue extends BaseDataStore
{
    protected $table = 'udl_values';
    protected $fillable = ['name', 'udlId', 'externalId'];
    protected $parentColumn = 'parentId';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('WA\DataStore\User\User', 'user_udls', 'udlValueId', 'userId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function udl()
    {
        return $this->belongsTo('WA\DataStore\Udl\Udl', 'udlId');
    }

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new UdlValueTransformer();
    }
}
