<?php

namespace WA\DataStore\Device;

use League\Fractal\Resource\Collection as ResourceCollection;
use WA\DataStore\DeviceType\DeviceTypeTransformer;
use WA\DataStore\FilterableTransformer;

/**
 * Class DeviceTransformer.
 */
class DeviceTransformer extends FilterableTransformer
{

    protected $availableIncludes = [
        'devicetypes',
        'modifications',
        'images',
        'devicevariations',
    ];

    /**
     * @param Device $device
     *
     * @return array
     */
    public function transform(Device $device)
    {
        return [
            'id'             => (int)$device->id,
            'identification' => $device->identification,
            'name'           => $device->name,
            'properties'     => $device->properties,
            'externalId'     => (int)$device->externalId,
            'statusId'       => (int)$device->statusId,
            'syncId'         => (int)$device->syncId,
            'make'           => $device->make,
            'model'          => $device->model,
            'defaultPrice'   => (int)$device->defaultPrice,
            'currency'       => $device->currency,
            'created_at'     => $device->created_at,
            'updated_at'     => $device->updated_at,
        ];
    }

    /**
     * Dynamic include override because of mixed case
     *
     * @param Device $device
     * @return ResourceCollection
     */
    public function includeDevicetypes(Device $device)
    {
        $this->criteria = $this->getRequestCriteria();
        $devicetypes = $this->applyCriteria($device->devicetypes(), null, true, [
            'devicetypes' => 'device_types'
        ]);
        return new ResourceCollection($devicetypes->get(), new DeviceTypeTransformer(), 'devicetypes');
    }
}
