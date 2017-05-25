<?php

namespace WA\DataStore\Content;

use WA\DataStore\FilterableTransformer;

/**
 * Class ContentTransformer.
 */
class ContentTransformer extends FilterableTransformer
{
    /**
     * @param Content $content
     *
     * @return array
     */
    public function transform(Content $content)
    {
        return [

            'id' => (int)$content->id,

            'content' => $content->content,

            'active' => $content->active,

            'owner_type' => $content->owner_type,

            'owner_id' => $content->owner_id,
        ];
    }
}
