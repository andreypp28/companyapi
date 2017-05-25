<?php

namespace WA\DataStore\Modification;

use WA\DataStore\FilterableTransformer;

/**
 * Class OrderTransformer.
 */
class ModificationTransformer extends FilterableTransformer
{
    /**
     * @param Modification $modification
     *
     * @return array
     */
    public function transform(Modification $modification)
    {
        return [
            'id'         => (int)$modification->id,
            'modType'    => $modification->modType,
            'value'      => $modification->value,
            'unit'       => $modification->unit,
            'created_at' => $modification->created_at,
            'updated_at' => $modification->updated_at,
        ];
    }
}
