<?php

namespace WA\DataStore\Package;

use WA\DataStore\BaseDataStore;

/**
 * Class Package.
 */
class Package extends BaseDataStore
{
    protected $table = 'packages';

    protected $fillable = [
            'name',
            'information',
            'companyId',
            'updated_at', ];

    /**
     * Get all the owners for the package.
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
     * @return PackageTransformer
     */
    public function getTransformer()
    {
        return new PackageTransformer();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conditions()
    {
        return $this->hasMany('WA\DataStore\Condition\Condition', 'packageId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany('WA\DataStore\Service\Service', 'package_services', 'packageId', 'serviceId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function devicevariations()
    {
        return $this->belongsToMany('WA\DataStore\DeviceVariation\DeviceVariation', 'package_devices', 'packageId', 'deviceVariationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function apps()
    {
        return $this->belongsToMany('WA\DataStore\App\App', 'package_apps', 'packageId', 'appId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('WA\DataStore\Order\Order', 'packageId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companies()
    {
        return $this->belongsTo('WA\DataStore\Company\Company', 'companyId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function addresses()
    {
        return $this->belongsToMany('WA\DataStore\Address\Address', 'package_address', 'packageId', 'addressId');
    }
}
