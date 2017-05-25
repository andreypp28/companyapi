<?php

namespace WA\DataStore\Company;

use WA\DataStore\BaseDataStore;
use Illuminate\Support\Facades\Auth;

/**
 * Class CompanyUserImportJob.
 *
 * @mixin \Eloquent
 */
class CompanyUserImportJob extends BaseDataStore
{
    const STATUS_PENDING = 0;
    const STATUS_WORKING = 1;
    const STATUS_SUSPENDED = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELED = 4;

    /**
     * make return packet
     *
     * @return mixed
     */
    public function getJobData($fresh=false) {
        if($fresh) {
            $this->fresh();
        }

        $userInterface = app()->make('WA\Repositories\User\UserInterface');
        //print_r($userInterface->getUdls(1)->toArray()); exit;

        $data = new \stdClass();
        $data->id = $this->id;
        $data->type = 'jobs';

        $attributes = new \stdClass();
        $attributes->status = $this->getStatusText();
        $attributes->total  = $this->total;
        $attributes->created = $this->created;
        $attributes->updated = $this->updated;
        $attributes->errors  = $this->failed;
        $attributes->sampleUser = unserialize($this->sample);
        $attributes->CSVfields  = unserialize($this->fields);
        $attributes->DBfields   = array_flip($userInterface->getMappableFields());
        $attributes->mappings   = unserialize($this->mappings);

        $data->attributes = $attributes;

        $result = new \stdClass();
        $result->data = $data;

        return $result;
    }

    /**
     * get status text
     *
     * @return string
     */
    public function getStatusText() {
        if($this->status == static::STATUS_PENDING) {
            return 'Pending';
        } else if($this->status == static::STATUS_WORKING) {
            return 'Working';
        } else if($this->status == static::STATUS_SUSPENDED) {
            return 'Suspended';
        } else if($this->status == static::STATUS_COMPLETED) {
            return 'Completed';
        } else if($this->status == static::STATUS_CANCELED) {
            return 'Canceled';
        }
    }
}
