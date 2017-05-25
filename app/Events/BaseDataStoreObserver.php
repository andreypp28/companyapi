<?php

namespace WA\Events;

use Cache;

/**
 * Observer to watch changes to any BaseDataStore eloquent model.
 */
class BaseDataStoreObserver
{
    /**
     * @param $tags
     */
    protected function clearCacheTags($tags)
    {
        Cache::tags($tags)->flush();
    }

    /**
     * @param $model
     */
    public function saved($model)
    {
        $this->clearCacheTags($model->getTable());
    }

    /**
     * @param $model
     */
    public function deleted($model)
    {
        $this->clearCacheTags($model->getTable());
    }

    /**
     * @param $model
     */
    public function restored($model)
    {
        $this->clearCacheTags($model->getTable());
    }
}
