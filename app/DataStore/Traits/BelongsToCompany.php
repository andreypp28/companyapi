<?php

namespace WA\DataStore\Traits;

/**
 * Class BelongsToCompany.
 */
trait BelongsToCompany
{
    /**
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo('WA\DataStore\Company\Company', 'companyId');
    }
}
