<?php

namespace WA\DataStore\ServiceItem;

use WA\DataStore\BaseDataStore;

/**
 * Class App.
 */
class ServiceItem extends BaseDataStore
{
    protected $table = 'service_items';

    protected $fillable = [
            'serviceId',
            'category',
            'description',
            'value',
            'unit',
            'cost',
            'domain',
            'updated_at', ];


    /**
     * Get the transformer instance.
     *
     * @return ServiceItemTransformer
     */
    public function getTransformer()
    {
        return new ServiceItemTransformer();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function service()
    {
        return $this->belongsTo('WA\DataStore\Service\Service', 'serviceId');
    }

}
