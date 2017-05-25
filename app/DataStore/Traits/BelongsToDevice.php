<?php

namespace WA\DataStore\Traits;

/**
 * Class BelongsToDevice.
 *
 * Physical devices that WA has in its inventory
 */
trait BelongsToDevice
{
    /**
     * @return \WA\DataStore\Device\Device {BelongsTo}
     */
    public function device()
    {
        return $this->belongsTo('WA\DataStore\Device\Device', 'deviceId');
    }
}
