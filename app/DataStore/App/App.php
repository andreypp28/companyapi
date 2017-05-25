<?php

namespace WA\DataStore\App;

use WA\DataStore\BaseDataStore;

/**
 * Class App.
 */
class App extends BaseDataStore
{
    protected $table = 'apps';

    protected $fillable = [
            'type',
            'image',
            'description',
            'updated_at', ];

    /**
     * Get all the owners for the app.
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
     * @return AppTransformer
     */
    public function getTransformer()
    {
        return new AppTransformer();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages()
    {
        return $this->belongsToMany('WA\DataStore\Package\Package', 'package_apps', 'packageId', 'appsId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany('WA\DataStore\Order\Order', 'order_apps', 'orderId', 'appId');
    }
}
