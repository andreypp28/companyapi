<?php

namespace WA\Repositories;

interface CountableInterface
{
    /**
     * Get the count of all unique types in the system.
     *
     * @param string $lastUpdated
     *
     * @return int of count
     */
    public function getCount($lastUpdated = null);
}
