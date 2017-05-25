<?php

namespace WA\DataStore\Traits;

use WA\DataStore\JobStatus;

/**
 * Class BelongsToJobStatus.
 */
trait BelongsToJobStatus
{
    /*
     * @return \WA\DataStore\BaseDataStore
     */
    /**
     * @return mixed
     */
    public function jobstatus()
    {
        return $this->belongsTo('WA\DataStore\JobStatus', 'statusId');
    }

    /**
     * @param $statusName
     *
     * @return \WA\DataStore\BaseDataStore
     */
    public function setStatusByName($statusName)
    {
        $this->statusId = JobStatus::whereName($statusName)->first()->id;

        return $this->save();
    }

    public function isStatus($statusName)
    {
        $statusId = JobStatus::whereName($statusName)->first()->id;
        if ($this->statusId == $statusId) {
            return true;
        }

        return false;
    }
}
