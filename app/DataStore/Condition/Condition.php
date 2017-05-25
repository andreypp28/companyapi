<?php

namespace WA\DataStore\Condition;

use WA\DataStore\BaseDataStore;
use WA\DataStore\Order\OrderTransformer;

/**
 * Class Order.
 */
class Condition extends BaseDataStore
{
    protected $table = 'conditions';

    protected $fillable = [
            'packageId',
            'name',
            'condition',
            'value',
        ];

  /**
   * Get all the owners for the order.
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
     * @return OrderTransformer
     */
    public function getTransformer()
    {
        return new ConditionTransformer();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages()
    {
        return $this->belongsTo('WA\DataStore\Package\Package', 'packageId');
    }
}
