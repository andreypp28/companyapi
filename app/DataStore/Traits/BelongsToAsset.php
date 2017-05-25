<?php

namespace WA\DataStore\Traits;

/**
 * Class BelongsToAsset.
 *
 * The Data store for all assets managed by WA -- independent of devices or mobile numbers
 */
trait BelongsToAsset
{
    /**
     * @return \WA\DataStore\Asset\Asset {BelongsTo}
     */
    public function asset()
    {
        return $this->belongsTo('WA\DataStore\Asset\Asset', 'assetId');
    }
}
