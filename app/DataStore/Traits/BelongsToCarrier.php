<?php

namespace WA\DataStore\Traits;

/**
 * Class BelongsToCarrier.
 */
trait BelongsToCarrier
{
    /**
     * @return \WA\DataStore\BaseDataStore
     */
    public function carrier()
    {
        return $this->belongsTo('WA\DataStore\Carrier\Carrier', 'carrierId');
    }
}
