<?php

namespace WA\DataStore\Device;

use WA\DataStore\MutableDataStore;
use WA\DataStore\Traits\BelongsToDeviceType;
use WA\DataStore\Traits\BelongsToJobStatus;

/**
 * An Eloquent Model: 'WA\DataStore\Device\Device'.
 *
 * @property int $id
 * @property int $type
 * @property string $identification
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Asset\Asset[] $assets
 * @property int $deviceTypeId
 * @property-read \WA\DataStore\DeviceType $deviceType
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Device\Device whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Device\Device whereIdentification($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Device\Device whereDeviceTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Device\Device whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Device\Device whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Attribute[] $attributes
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\User\User[] $users
 * @property-read \WA\DataStore\Carrier\Carrier $carrier
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Company\Company[] $companies
 * @property-read \WA\DataStore\SyncJob $sync
 * @property-read \WA\DataStore\JobStatus $jobstatus
 * @mixin \Eloquent
 */
class Device extends MutableDataStore
{
    protected $table = 'devices';

    use BelongsToJobStatus;
    use BelongsToDeviceType;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'properties',
        'deviceTypeId',
        'statusId',
        'externalId',
        'identification',
        'syncId',
        'make',
        'model',
        'defaultPrice',
        'currency'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modifications()
    {
        return $this->belongsToMany('WA\DataStore\Modification\Modification', 'device_modifications', 'deviceId', 'modificationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images()
    {
        return $this->belongsToMany('WA\DataStore\Image\Image', 'device_images', 'deviceId', 'imageId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function devicevariations()
    {
        return $this->hasMany('WA\DataStore\DeviceVariation\DeviceVariation', 'deviceId');
    }

    /**
     * @return $this
     */
    public function attributes()
    {
        return $this->morphToMany('WA\DataStore\Attribute', 'attributable')->withPivot(['value', 'dataOriginationId']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sync()
    {
        return $this->belongsTo('WA\DataStore\SyncJob', 'statusId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function devicetypes()
    {
        return $this->belongsTo('WA\DataStore\DeviceType\DeviceType', 'deviceTypeId');
    }

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new DeviceTransformer();
    }
}
