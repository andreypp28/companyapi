<?php

namespace WA\DataStore\Condition;

use WA\DataStore\FilterableTransformer;

/**
 * Class ConditionTransformer.
 */
class ConditionFieldTransformer extends FilterableTransformer
{
    /**
     * @param ConditionField $conditionField
     *
     * @return array
     */
    public function transform(ConditionField $conditionField)
    {
        return [

            'id'        => (int)$conditionField->id,
            'typeField' => $conditionField->typeField,
            'field'     => $conditionField->field,
        ];
    }
}
