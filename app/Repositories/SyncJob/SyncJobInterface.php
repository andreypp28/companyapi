<?php

namespace WA\Repositories\SyncJob;

use WA\Repositories\RepositoryInterface;

interface SyncJobInterface extends RepositoryInterface
{
    /**
     * Get the status by the Name.
     *
     * @param $name
     *
     * @return object object of the sync |
     */
    public function statusIdByName($name);

    /**
     * Return the last time sync.
     *
     * @param string $type   of sync
     * @param string $status of the sync
     *
     * @return \DateTime
     */
    public function getLastSyncTime($type, $status = null);

    /**
     * Get all syncs by the sync name.
     *
     * @param string $name
     * @param string $status of the sync
     * @param int    $limit
     *
     * @return object object of sync
     */
    public function byName($name, $status, $limit);
}
