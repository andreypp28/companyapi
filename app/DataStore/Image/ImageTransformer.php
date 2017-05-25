<?php

namespace WA\DataStore\Image;

use WA\DataStore\FilterableTransformer;

/**
 * Class ImageTransformer.
 */
class ImageTransformer extends FilterableTransformer
{
    /**
     * @param Image $image
     *
     * @return array
     */
    public function transform(Image $image)
    {
        return [

            'id'           => (int)$image->id,
            'originalName' => $image->originalName,
            'filename'     => $image->filename,
            'mimeType'     => $image->mimeType,
            'extension'    => $image->extension,
            'size'         => (int)$image->size,
            'url'          => $image->url,
            'created_at'   => $image->created_at,
            'updated_at'   => $image->updated_at,
        ];
    }
}
