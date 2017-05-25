<?php

namespace WA\DataStore\Preset;

use WA\DataStore\BaseDataStore;

class Preset extends BaseDataStore
{
    protected $table = 'presets';

    protected $fillable = [
            'name',
            'companyId'
            ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function devicevariations()
    {
        return $this->belongsToMany('WA\DataStore\DeviceVariation\DeviceVariation', 'preset_device_variations', 'presetId', 'deviceVariationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companies()
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
        return new PresetTransformer();
    }
}
