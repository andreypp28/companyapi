<?php

namespace WA\Helpers\Traits;

use App;
use DB;

/**
 * Class SetLimits.
 */
trait SetLimits
{
    /**
     * Set execution limits.
     */
    final public function setLimits()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        if (!App::environment('local')) {
            DB::disableQueryLog();
        }
    }
}
