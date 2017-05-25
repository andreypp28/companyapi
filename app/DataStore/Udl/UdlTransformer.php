<?php

namespace WA\DataStore\Udl;

use WA\DataStore\FilterableTransformer;

/**
 * Class UdlTransformer.
 */
class UdlTransformer extends FilterableTransformer
{
    protected $availableIncludes = [
        'udlvalues'
    ];

    /**
     * @param Udl $udl
     *
     * @return array
     */
    public function transform(Udl $udl)
    {
        return [
            'id'    => $udl->id,
            'companyId'    => $udl->companyId,
            'name'  => $udl->name,
            'inputType' => $udl->inputType,
            'sections' => $udl->udlValues,
            'legacyUdlField' => $udl->legacyUdlField,
        ];
    }
}
