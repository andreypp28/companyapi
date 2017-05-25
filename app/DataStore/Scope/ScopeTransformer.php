<?php

namespace WA\DataStore\Scope;

use WA\DataStore\FilterableTransformer;

/**
 * Class ScopeTransformer.
 */
class ScopeTransformer extends FilterableTransformer
{
     protected $availableIncludes = [
        'permissions',
        
    ];

    /**
     * @param Scope $scope
     *
     * @return array
     */
    public function transform(Scope $scope)
    {
        return [
            'id'   => (int)$scope->id,
            'name' => $scope->display_name,
            //'display_name' => $scope->display_name,
            //'description' => $scope->description,


        ];
    }
}
