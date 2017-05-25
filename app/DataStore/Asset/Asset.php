<?php

namespace WA\DataStore\Asset;

use Illuminate\Database\Eloquent\SoftDeletes as SoftDeletingTrait;
use WA\DataStore\MutableDataStore;

/**
 * WA\DataStore\Asset\Asset.
 *
 * @property int $id
 * @property string $identification
 * @property bool $isActive
 * @property int $externalId
 * @property int $typeId
 * @property int $carrierId
 * @property int $statusId
 * @property int $syncId
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\User\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Device\Device[] $devices
 * @property-read \WA\DataStore\SyncJob $sync
 * @property-read \WA\DataStore\JobStatus $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\WirelessLineDetail[] $wirelessLineDetails
 * @property-read \WA\DataStore\AssetType $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Attribute[] $attributes
 * @property-read \WA\DataStore\Carrier\Carrier $carrier
 * @property-read \Illuminate\Database\Eloquent\Collection|\$related[] $morphedByMany
 *
 * @method static \Illuminate\Database\Query\Builder|\\WA\DataStore\Asset\Asset whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Asset\Asset whereIdentification($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Asset\Asset whereIsActive($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Asset\Asset whereExternalId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Asset\Asset whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Asset\Asset whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Asset\Asset whereStatusId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Asset\Asset whereSyncId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Asset\Asset whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Asset\Asset whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Asset\Asset whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Asset extends MutableDataStore
{
    use SoftDeletingTrait;

    protected $table = 'assets';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'userId',
        'statusId',
        'typeId',
        'identification',
        'externalId',
        'id',
        'isActive',
        'syncId',
    ];

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new AssetTransformer();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsTo('WA\DataStore\User\User', 'userId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sync()
    {
        return $this->belongsTo('WA\DataStore\SyncJob', 'syncId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('WA\DataStore\JobStatus', 'statusId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo('WA\DataStore\AssetType', 'typeId');
    }

    /**
     * Case insensitive check for status.
     *
     * @param $name
     *
     * @return bool
     */
    public function isStatus($name)
    {
        if (strtolower($this->getStatus()) == (strtolower($name))) {
            return true;
        }

        return false;
    }

    /**
     * @return bool|mixed
     */
    public function getStatus()
    {
        $statusCheck = $this->attributes()->where('name', 'Status')->first();
        if ($statusCheck === null) {
            return false;
        }

        return $statusCheck->pivot->value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attributes()
    {
        return $this->morphToMany('WA\DataStore\Attribute', 'attributable')->withPivot(['value', 'dataOriginationId']);
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function hasDevice($id)
    {
        return !$this->devices->filter(
            function ($device) use ($id) {
                return $device->id == $id;
            }
        )->isEmpty();
    }
}
