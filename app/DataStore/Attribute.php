<?php

namespace WA\DataStore;

/**
 * An Eloquent Model: 'WA\DataStore\Attribute'.
 *
 * @property int $id
 * @property string $name
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Attribute whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Attribute whereName($value)
 * @mixin \Eloquent
 */
class Attribute extends BaseDataStore
{
    protected $table = 'attributes';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function devices()
    {
        return $this->morphedByMany('Device', 'attributable')->withPivot(['value', 'dataOriginationId']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function assets()
    {
        return $this->morphedByMany('Asset', 'attributable')->withPivot(['value', 'dataOriginationId']);
    }
}
