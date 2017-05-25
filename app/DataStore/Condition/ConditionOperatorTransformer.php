<?php

namespace WA\DataStore\Condition;

use WA\DataStore\FilterableTransformer;

/**
 * Class ConditionTransformer.
 */
class ConditionOperatorTransformer extends FilterableTransformer
{
    /**
     * @param ConditionOperator $conditionOperator
     *
     * @return array
     */
    public function transform(ConditionOperator $conditionOperator)
    {
        return [

            'id'           => (int)$conditionOperator->id,
            'originalName' => $conditionOperator->originalName,
            'apiName'      => $conditionOperator->apiName,
        ];
    }
}
