<?php

namespace WA\DataStore\Asset;

use WA\DataStore\FilterableTransformer;

/**
 * Class AssetTransformer.
 */
class AssetTransformer extends FilterableTransformer
{
    protected $availableIncludes = [
        'users',
    ];

    /**
     * @param Asset $asset
     *
     * @return array
     */
    public function transform(Asset $asset)
    {
        return [
            'id'                    => (int)$asset->id,
            'identification'        => $asset->identification,
            'active'                => $asset->active,
            'statusId'              => $asset->statusId,
            'typeId'                => $asset->typeId,
            'externalId'            => $asset->externalId,
            'carrierId'             => $asset->carrierId,
            'syncId'                => $asset->syncId,
            'userId'                => $asset->userId,
        ];
    }
}
