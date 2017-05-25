<?php

namespace WA\DataStore\Request;

use WA\DataStore\BaseDataStore;

/**
 * Class Request.
 */
class Request extends BaseDataStore
{
    protected $table = 'requests';

    protected $fillable = array('name', 'description');

  /**
   * Get all the owners for the Request.
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
     * @return RequestTransformer
     */
    public function getTransformer()
    {
        return new RequestTransformer();
    }
}
