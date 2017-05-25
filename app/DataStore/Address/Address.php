<?php

namespace WA\DataStore\Address;

use WA\DataStore\BaseDataStore;

/**
 * Class Address.
 */
class Address extends BaseDataStore
{
    protected $table = 'address';

    protected $fillable = [
            'name',
            'attn',
            'phone',
            'address',
            'city',
            'state',
            'country',
            'postalCode',
            'updated_at', ];

    /**
     * Get all the owners for the address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('WA\DataStore\User\User', 'user_address', 'userId', 'addressId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany('WA\DataStore\Company\Company', 'company_address', 'companyId', 'addressId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages()
    {
        return $this->belongsToMany('WA\DataStore\Package\Package', 'package_address', 'packageId', 'addressId');
    }

    /**
     * Get the transformer instance.
     *
     * @return AddressTransformer
     */
    public function getTransformer()
    {
        return new AddressTransformer();
    }
}
