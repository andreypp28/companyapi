<?php

namespace WA\Services\Cache;

use Illuminate\Cache\CacheManager;

/**
 * Class Cache.
 */
class Cache implements CacheInterface
{
    protected $cache;
    protected $cacheKey;
    protected $minutes;

    /**
     * @param CacheManager $cache
     * @param $cacheKey
     * @param null $minutes
     */
    public function __construct(CacheManager $cache, $cacheKey, $minutes = null)
    {
        $this->cache = $cache;
        $this->cacheKey = $cacheKey;
        $this->minutes = $minutes;
    }

    /**
     * Retrieve data from cache.
     *
     * @param string    Cache item key
     *
     * @return mixed data result of cache
     */
    public function get($key)
    {
        return $this->cache->tags($this->cacheKey)->get($key);
    }

    /**
     * Add data to the cache.
     *
     * @param string    Cache item key
     * @param mixed     The data to store
     * @param int   The number of minutes to store the item
     *
     * @return mixed $value variable returned for convenience
     */
    public function put($key, $value, $minutes = null)
    {
        if (is_null($minutes)) {
            $minutes = $this->minutes;
        }

        return $this->cache->tags($this->cacheKey)->put($key, $value, $minutes);
    }

    /**
     * Add data to the cache
     * taking pagination data into account.
     *
     * @param int   Page of the cached items
     * @param int   Number of results per page
     * @param int   Total number of possible items
     * @param mixed     The actual items for this page
     * @param string    Cache item key
     * @param int   The number of minutes to store the item
     *
     * @return mixed $items variable returned for convenience
     */
    public function putPaginated($currentPage, $perPage, $totalItems, $items, $key, $minutes = null)
    {
        $cached = new \StdClass();

        $cached->currentPage = $currentPage;
        $cached->items = $items;
        $cached->totalItems = $totalItems;
        $cached->perPage = $perPage;

        $this->put($key, $cached, $minutes);

        return $cached;
    }

    /**
     * Test if item exists in cache
     * Only returns true if exists && is not expired.
     *
     * @param string    Cache item key
     *
     * @return bool If cache item exists
     */
    public function has($key)
    {
        return $this->cache->tags($this->cacheKey)->has($key);
    }
}
