<?php

namespace WA\DataStore\Relationship;

use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class RelationshipTransformer extends TransformerAbstract
{
    /**
     * @param Class $class
     *
     * @return array
     */
    public function transform($class)
    {
        return [
            'id' => $class->id,
        ];
    }
}
