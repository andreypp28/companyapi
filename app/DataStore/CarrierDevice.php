<?php

namespace WA\DataStore;

use WA\DataStore\Traits\BelongsToCarrier;
use WA\DataStore\Traits\BelongsToDeviceType;
use WA\DataStore\Traits\BelongsToJobStatus;

/**
 * An Eloquent Model: 'WA\DataStore\CarrierDevice'.
 *
 * @property int $id
 * @property int $carrierId
 * @property string $make
 * @property string $model
 * @property string $makeModel
 * @property string $WA_alias
 * @property string $class
 * @property string $deviceOS
 * @property string $description
 * @property-read \WA\DataStore\Carrier\Carrier $carriers
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereCarrierId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereMake($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereModel($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereMakeModel($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereWAAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereClass($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereDeviceOS($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereDescription($value)
 *
 * @property int $statusId
 * @property int $deviceTypeId
 * @property-read \WA\DataStore\DeviceType $deviceType
 * @property-read \WA\DataStore\JobStatus $jobstatus
 * @property-read \WA\DataStore\Carrier\Carrier $carrier
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereJobStatusId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\CarrierDevice whereDeviceTypeId($value)
 * @mixin \Eloquent
 */
class CarrierDevice extends MutableDataStore
{
    public $timestamps = false;
    protected $table = 'carrier_devices';
    protected $fillable = [
        'carrierId',
        'make',
        'model',
        'makeModel',
        'WA_alias',
        'class',
        'deviceOS',
        'description',
        'deviceTypeId',
        'statusId',
    ];

    use BelongsToDeviceType, BelongsToJobStatus, BelongsToCarrier;
}
