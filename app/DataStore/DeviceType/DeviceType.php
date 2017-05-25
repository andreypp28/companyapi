<?php

namespace WA\DataStore\DeviceType;

use WA\DataStore\Traits\BelongsToJobStatus;
use WA\DataStore\MutableDataStore;

/**
 * An Eloquent Model: 'WA\DataStore\DeviceType'.
 *
 * @property int $id
 * @property string $make
 * @property string $model
 * @property string $class
 * @property string $deviceOS
 * @property string $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\DeviceType[] $devices
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\DeviceType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\DeviceType whereMake($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\DeviceType whereModel($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\DeviceType whereClass($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\DeviceType whereDeviceOS($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\DeviceType whereDescription($value)
 *
 * @property int $statusId
 * @property-read \WA\DataStore\ $jobstatus
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\DeviceType whereJobStatusId($value)
 *
 * @property-read \WA\DataStore\CarrierDevice $carrierDevice
 * @mixin \Eloquent
 */
class DeviceType extends MutableDataStore
{
    public $timestamps = false;
    protected $table = 'device_types';

    protected $fillable = [
        'name',
        'statusId',
    ];

    use BelongsToJobStatus;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes = []);
        if (!empty($this->isLiveClient)) {
            $this->setTable('clone_device_types');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany('WA\DataStore\Device\Device', 'deviceTypeId');
    }

    public function carrierDevice()
    {
        return $this->hasOne('WA\DataStore\CarrierDevice', 'deviceTypeId');
    }

    /**
     * Get the transformer instance.
     *
     * @return ImageTransformer
     */
    public function getTransformer()
    {
        return new DeviceTypeTransformer();
    }
}
