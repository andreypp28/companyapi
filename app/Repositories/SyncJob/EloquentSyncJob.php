<?php

namespace WA\Repositories\SyncJob;

use Illuminate\Database\Eloquent\Model;
use WA\Repositories\AbstractRepository;
use WA\Repositories\JobStatus\JobStatusInterface as StatusInterface;

class EloquentSyncJob extends AbstractRepository implements SyncJobInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var StatusInterface
     */
    protected $status;

    public function __construct(Model $model, StatusInterface $status)
    {
        $this->model = $model;
        $this->status = $status;
    }

    /**
     * @param $name
     *
     * @return int | null
     */
    public function statusIdByName($name)
    {
        return $this->model->where('name', $name)
            ->pluck('id');
    }

    /**
     * Return the last time sync.
     *
     * @param string $type   of sync
     * @param string $status of the sync
     *
     * @return \DateTime
     */
    public function getLastSyncTime($type, $status = null)
    {
        $response = $this->model->where('name', $type);

        if (!is_null($status)) {
            $statusId = $this->status->idByName($status);
            $response->where('statusId', $statusId);
        }

        $result = $response->max('created_at');

        return (!is_null($result)) ? $result : null;
    }

    /**
     * Get all syncs by the sync name.

     *
     * @param string $name
     * @param string $status of the sync
     * @param int    $limit
     *
     * @return object object of sync
     */
    public function byName($name, $status, $limit)
    {
        $response =
            $this->model->where('name', $name);

        if (!is_null($status)) {
            $statusId = $this->status->idByName($status);
            $response->where('statusId', $statusId);
        }

        $result = $response->orderBy('created_at', 'DESC')
            ->take($limit)
            ->get();

        return $result;
    }
}
