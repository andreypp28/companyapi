<?php

namespace WA\DataStore\Condition;

use WA\DataStore\FilterableTransformer;

/**
 * Class ConditionTransformer.
 */
class ConditionTransformer extends FilterableTransformer
{
    /**
     * @param Condition $condition
     *
     * @return array
     */
    public function transform(Condition $condition)
    {
        return [

            'id'         => (int)$condition->id,
            'packageId'  => (int)$condition->packageId,
            'nameCond'   => $condition->name,
            'condition'  => $condition->condition,
            'value'      => $condition->value,
            'created_at' => $condition->created_at,
            'updated_at' => $condition->updated_at,
        ];
    }
}
