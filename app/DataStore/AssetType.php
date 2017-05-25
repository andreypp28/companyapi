<?php

namespace WA\DataStore;

/**
 * An Eloquent Model: 'WA\DataStore\AssetType'.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\AssetType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\AssetType whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\AssetType whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\AssetType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\AssetType whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Asset\Asset[] $assets
 * @mixin \Eloquent
 */
class AssetType extends BaseDataStore
{
    protected $tableName = 'asset_types';

    protected $fillable = ['name', 'description'];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets()
    {
        return $this->hasMany('WA\DataStore\Asset\Asset', 'type');
    }
}
