<?php

namespace WA\DataStore\Category;

use WA\DataStore\BaseDataStore;

class CategoryApp extends BaseDataStore
{
    protected $table = 'categoryapps';

    protected $fillable = [
            'name',
            ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function apps()
    {
        return $this->belongsToMany('WA\DataStore\App\App', 'categoryapps_app', 'categoryappId', 'appId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images()
    {
        return $this->belongsToMany('WA\DataStore\Image\Image', 'categoryapps_image', 'categoryappId', 'imageId');
    }

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new CategoryAppTransformer();
    }
}
