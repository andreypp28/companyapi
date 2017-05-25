<?php

namespace WA\DataStore\Condition;

use WA\DataStore\BaseDataStore;
use WA\DataStore\Order\OrderTransformer;

/**
 * Class Order.
 */
class ConditionOperator extends BaseDataStore
{
    protected $table = 'condition_operators';

    protected $fillable = [
            'originalName',
            'apiName',
        ];

    public $timestamps = false;

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
        return new ConditionOperatorTransformer();
    }
}
