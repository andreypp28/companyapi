<?php

namespace WA\DataStore\Order;

use WA\DataStore\BaseDataStore;

/**
 * Class Order.
 */
class Order extends BaseDataStore
{
    protected $table = 'orders';

    protected $fillable = [
            'status',
            'userId',
            'packageId',
            'serviceId',
            'addressId',
            'updated_at', ];

    /**
     * Get all the owners for the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * Get the transformer instance.
     *
     * @return OrderTransformer
     */
    public function getTransformer()
    {
        return new OrderTransformer();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function apps()
    {
        return $this->belongsToMany('WA\DataStore\App\App', 'order_apps', 'orderId', 'appId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deviceVariations()
    {
        return $this->belongsToMany('WA\DataStore\DeviceVariation\DeviceVariation', 'order_device_variations', 'orderId', 'deviceVariationId');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users()
    {
        return $this->belongsTo('WA\DataStore\User\User', 'userId');
    }

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function packages()
    {
        return $this->belongsTo('WA\DataStore\Package\Package', 'packageId');
    }    

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function services()
    {
        return $this->belongsTo('WA\DataStore\Service\Service', 'serviceId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function addresses()
    {
        return $this->belongsTo('WA\DataStore\Address\Address', 'addressId');
    }
}
