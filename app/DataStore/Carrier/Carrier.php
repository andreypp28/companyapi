<?php

namespace WA\DataStore\Carrier;

use WA\DataStore\BaseDataStore;

/**
 * An Eloquent Model: 'WA\DataStore\Carrier\Carrier'.
 *
 * @property int $id
 * @property string $name
 * @property bool $active
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Dump[] $dumps
 * @property-read \Illuminate\Database\Eloquent\Collection|\Client[] $clients
 * @property int $dataSourceCount
 * @property int $rawDataFilesCount
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Adjustable[] $adjustables
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Carrier\Carrier whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Carrier\Carrier whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Carrier\Carrier whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\Carrier\Carrier whereRawDataFilesCount($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\User\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Carrier\Carrier[] $companies
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Asset\Asset[] $assets
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Device\Device[] $devices
 * @property-read \WA\DataStore\Location\Location $location
 * @mixin \Eloquent
 */
class Carrier extends BaseDataStore
{
    protected $table = 'carriers';

    protected $fillable = [
            'name',
            'presentation',
            'active',
            'locationId',
            'shortName',
            'updated_at', ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo('WA\DataStore\Location\Location', 'locationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images()
    {
        return $this->belongsToMany('WA\DataStore\Image\Image', 'carrier_images', 'carrierId', 'imageId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany('WA\DataStore\Service\Service', 'carrierId');
    }   

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devicevariations()
    {
        return $this->hasMany('WA\DataStore\DeviceVariation\DeviceVariation', 'carrierId');
    }
    
    public function allocations()
    {
        return $this->hasMany('WA\DataStore\Allocation\Allocation', 'carrier');
    }

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new CarrierTransformer();
    }
}
