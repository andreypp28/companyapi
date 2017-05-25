<?php

namespace WA\DataStore\Request;

use WA\DataStore\FilterableTransformer;

/**
 * Class RequestTransformer.
 */
class RequestTransformer extends FilterableTransformer
{
    /**
     * @param Request $Request
     *
     * @return array
     */
    public function transform(Request $request)
    {
        return [

            'id'          => (int)$request->id,
            'name'        => $request->name,
            'description' => $request->description,
            'type'        => $request->type,
            'created_at'  => $request->created_at,
            'updated_at'  => $request->updated_at,
        ];
    }
}
