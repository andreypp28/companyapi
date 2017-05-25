<?php

namespace WA\DataStore\Category;

use WA\DataStore\FilterableTransformer;

/**
 * Class CarrierTransformer.
 */
class CategoryAppTransformer extends FilterableTransformer
{
    protected $availableIncludes = [
        'apps',
        'images',
    ];

    /**
     * @param CategoryApp $categoryApp
     * @return array
     */
    public function transform(CategoryApp $categoryApp)
    {
        return [
            'id'         => $categoryApp->id,
            'name'       => $categoryApp->name,
            'created_at' => $categoryApp->created_at,
            'updated_at' => $categoryApp->updated_at,
        ];
    }
}
