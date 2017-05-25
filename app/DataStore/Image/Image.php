<?php

namespace WA\DataStore\Image;

use WA\DataStore\BaseDataStore;

/**
 * Class Image.
 */
class Image extends BaseDataStore
{
    protected $table = 'images';

    protected $fillable = [
                'originalName',
                'filename',
                'mimeType',
                'extension',
                'size',
                'url',
                'update_at', ];

    /**
     * Get all the owners for the Image.
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
     * @return ImageTransformer
     */
    public function getTransformer()
    {
        return new ImageTransformer();
    }
}
