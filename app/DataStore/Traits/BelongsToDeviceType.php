<?php

namespace WA\DataStore\Traits;

/**
 * Class BelongsToDeviceType.
 *
 * WA Device types
 */
trait BelongsToDeviceType
{
    /**
     * @return \WA\DataStore\DeviceType {BelongsTo}
     */
    public function deviceType()
    {
        return $this->belongsTo('WA\DataStore\DeviceType\DeviceType', 'deviceTypeId');
    }
}
