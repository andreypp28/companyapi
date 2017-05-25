<?php

namespace WA\DataStore\Package;

use WA\DataStore\FilterableTransformer;

/**
 * Class PackageTransformer.
 */
class PackageTransformer extends FilterableTransformer
{

    protected $availableIncludes = [
        'apps',
        'orders',
        'devicevariations',
        'services',
        'companies',
        'conditions',
        'addresses',        
    ];

    /**
     * @param Package $package
     * @return array
     */
    public function transform(Package $package)
    {
        return [
            'id'         => (int)$package->id,
            'name'       => $package->name,
            'information'=> $package->information,
            'companyId'  => (int)$package->companyId,
            'created_at' => $package->created_at,
            'updated_at' => $package->updated_at,
        ];
    }

}
