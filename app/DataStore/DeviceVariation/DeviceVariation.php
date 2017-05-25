<?php

namespace WA\DataStore\DeviceVariation;

use WA\DataStore\MutableDataStore;

class DeviceVariation extends MutableDataStore
{
    protected $table = 'device_variations';

    public $timestamps = true;

    protected $fillable = [
        'priceRetail',
        'price1',
        'price2',
        'priceOwn',
        'deviceId',
        'carrierId',
        'companyId',
    ];

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new DeviceVariationTransformer();
    }
     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images()
    {
        return $this->belongsToMany('WA\DataStore\Image\Image', 'deviceVariation_images', 'deviceVariationId', 'imageId');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function modifications()
    {
        return $this->belongsToMany('WA\DataStore\Modification\Modification', 'device_variations_modifications', 'deviceVariationId', 'modificationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function presets()
    {
        return $this->belongsToMany('WA\DataStore\Preset\Preset', 'preset_device_variations', 'presetId', 'deviceVariationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages()
    {
        return $this->belongsToMany('WA\DataStore\Package\Package', 'package_devices', 'packageId', 'deviceVariationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('WA\DataStore\User\User', 'user_device_variations', 'userId', 'deviceId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carriers()
    {
        return $this->belongsTo('WA\DataStore\Carrier\Carrier', 'carrierId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companies()
    {
        return $this->belongsTo('WA\DataStore\Company\Company', 'companyId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function devices()
    {
        return $this->belongsTo('WA\DataStore\Device\Device', 'deviceId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany('WA\DataStore\Order\Order', 'order_device_variations', 'orderId', 'deviceId');
    }
}
