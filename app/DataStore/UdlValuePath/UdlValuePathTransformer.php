<?php

namespace WA\DataStore\UdlValuePath;

use WA\DataStore\FilterableTransformer;

/**
 * Class UdlValuePathTransformer.
 */
class UdlValuePathTransformer extends FilterableTransformer
{
    /**
     * @param UdlValuePath $udlValuePath
     *
     * @return array
     */
    public function transform(UdlValuePath $udlValuePath)
    {
        return [
            'id'          => $udlValuePath->id,
            'name'        => $udlValuePath->udlPath,
            'lastUpdated' => $udlValuePath->updated_at,
        ];
    }
}
