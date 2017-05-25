<?php

namespace WA\DataStore;

/**
 * An Eloquent Model: 'WA\DataStore\JobStatus'.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Dump[] $dump
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\CarrierDump[] $carrierDumps
 *
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\JobStatus whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\JobStatus whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\WA\DataStore\JobStatus whereDescription($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\SyncJob[] $syncs
 * @mixin \Eloquent
 */
class JobStatus extends BaseDataStore
{
    protected $table = 'job_statuses';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function syncs()
    {
        return $this->hasMany('WA\DataStore\SyncJob', 'statusId');
    }
}
