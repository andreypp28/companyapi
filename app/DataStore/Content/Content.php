<?php

namespace WA\DataStore\Content;

use WA\DataStore\BaseDataStore;

/**
 * Class Content.
 */
class Content extends BaseDataStore
{
    protected $table = 'contents';

    protected $fillable = array('content', 'active', 'owner_type', 'owner_id');

  /**
   * Get all the owners for the page.
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
     * @return PageTransformer
     */
    public function getTransformer()
    {
        return new ContentTransformer();
    }
}
