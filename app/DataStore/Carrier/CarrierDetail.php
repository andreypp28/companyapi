<?php

namespace WA\DataStore\Carrier;

use WA\DataStore\BaseDataStore;

/**
 * Class CarrierDetail.
 *
 * @property-read \WA\DataStore\Carrier\Carrier $carrier
 * @property-read \WA\DataStore\Company\Company $company
 * @mixin \Eloquent
 */
class CarrierDetail extends BaseDataStore
{
    protected $table = 'account_line_summaries';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carrier()
    {
        return $this->belongsTo('WA\DataStore\Carrier\Carrier', 'carrierId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('WA\DataStore\Company\Company', 'companyId');
    }

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new CarrierDetailTransformer();
    }
}
