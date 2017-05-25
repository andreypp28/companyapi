<?php

namespace WA\DataStore\Modification;

use WA\DataStore\BaseDataStore;

/**
 * Class Modification.
 */
class Modification extends BaseDataStore
{
    protected $table = 'modifications';

    protected $fillable = [
        'modType',
        'value',
        'unit',
        'updated_at',
    ];

    /**
     * Get all the owners for the modifications.
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
     * @return ModificationTransformer
     */
    public function getTransformer()
    {
        return new ModificationTransformer();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function devices()
    {
        return $this->belongsToMany('WA\DataStore\Device\Device', 'device_modifications', 'deviceId', 'modificationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function devicesvariations()
    {
        return $this->belongsToMany('WA\DataStore\DeviceVariation\DeviceVariation', 'device_variations_modifications', 'deviceVariationId', 'modificationId');
    }
}
