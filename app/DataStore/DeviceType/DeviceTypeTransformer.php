<?php

namespace WA\DataStore\DeviceType;

use WA\DataStore\FilterableTransformer;

/**
 * Class AppTransformer.
 */
class DeviceTypeTransformer extends FilterableTransformer
{
    /**
     * @param DeviceType $deviceType
     *
     * @return array
     */
    public function transform(DeviceType $deviceType)
    {
        return [
            'id'          => (int)$deviceType->id,
            'name'        => $deviceType->name,
            'statusId'    => $deviceType->statusId,
        ];
    }
}
