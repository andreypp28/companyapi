<?php

namespace WA\DataStore\Preset;

use WA\DataStore\FilterableTransformer;

/**
 * Class CarrierTransformer.
 */
class PresetTransformer extends FilterableTransformer
{
    protected $availableIncludes = [
        'devicevariations',
        'companies',
    ];

    /**
     * @param Preset $preset
     *
     * @return array
     */
    public function transform(Preset $preset)
    {
        return [
            'id'         => $preset->id,
            'name'       => $preset->name,
            'companyId'  => $preset->companyId,
            'created_at' => $preset->created_at,
            'updated_at' => $preset->updated_at,
        ];
    }

}
