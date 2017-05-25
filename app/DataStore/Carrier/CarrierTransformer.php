<?php

namespace WA\DataStore\Carrier;

use WA\DataStore\FilterableTransformer;

/**
 * Class CarrierTransformer.
 */
class CarrierTransformer extends FilterableTransformer
{

    protected $availableIncludes = [
        'images',
        'services',
        'devicevariations'
    ];

    /**
     * @param Carrier $carrier
     *
     * @return array
     */
    public function transform(Carrier $carrier)
    {
        return [
            'id'           => (int)$carrier->id,
            'name'         => $carrier->name,
            'presentation' => $carrier->presentation,
            'active'       => $carrier->active,
            'locationId'   => $carrier->locationId,
            'shortName'    => $carrier->shortName,
            'created_at'   => $carrier->created_at,
            'updated_at'   => $carrier->updated_at,
        ];
    }

}
