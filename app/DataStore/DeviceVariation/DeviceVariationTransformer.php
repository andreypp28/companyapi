<?php

namespace WA\DataStore\DeviceVariation;

use WA\DataStore\FilterableTransformer;

/**
 * Class DeviceTransformer.
 */
class DeviceVariationTransformer extends FilterableTransformer
{
    protected $availableIncludes = [
        'modifications',
        'carriers',
        'companies',
        'devices',
        'presets',
        'users',
        'orders',
        'packages',
        'images'
    ];

    /**
     * @param DeviceVariation $deviceVariation
     * @return array
     */
    public function transform(DeviceVariation $deviceVariation)
    {
        return [
            'id'          => (int)$deviceVariation->id,
            'priceRetail' => (int)$deviceVariation->priceRetail,
            'price1'      => (int)$deviceVariation->price1,
            'price2'      => (int)$deviceVariation->price2,
            'priceOwn'    => (int)$deviceVariation->priceOwn,
            'deviceId'    => (int)$deviceVariation->deviceId,
            'carrierId'   => (int)$deviceVariation->carrierId,
            'companyId'   => (int)$deviceVariation->companyId,
            'created_at'  => $deviceVariation->created_at,
            'updated_at'  => $deviceVariation->updated_at,
        ];
    }

    /**
     * @param DeviceVariation $deviceVariation
     *
     * @return ResourceCollection Allocations
     */
    public function includeAllocations(DeviceVariation $deviceVariation)
    {
        $this->criteria = $this->getRequestCriteria();
        $modifications = $this->applyCriteria($deviceVariation->modifications(), $this->criteria, true,
            ['modifications' => 'modifications']);

        return new ResourceCollection($modifications->get(), new ModificationTransformer(), 'modifications');
    }
}
