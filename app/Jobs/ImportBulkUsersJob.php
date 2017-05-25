<?php

namespace WA\Jobs;

use WA\DataStore\Company\CompanyUserImportJob;
use WA\Helpers\Vendors\CSVParser;

class ImportBulkUsersJob extends Job
{
    protected $jobId;

    /**
     * ImportBulkUsersJob constructor.
     * @param $jobId
     */
    public function __construct($jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $userInterface = app()->make('WA\Repositories\User\UserInterface');

        $job = CompanyUserImportJob::find($this->jobId);
        if($job == null
            || $job->status != CompanyUserImportJob::STATUS_PENDING
            || (($job->created + $job->updated) >= $job->total)
            || ($job->failed >= $job->total)) {
            return;
        }

        // update job status to working
        $job->status = CompanyUserImportJob::STATUS_WORKING;
        $job->save();

        // start importing/updating
        $filePath = storage_path() . DIRECTORY_SEPARATOR . $job->path . DIRECTORY_SEPARATOR . $job->file;
        $csvParser = new CSVParser($filePath);
        $rows = $csvParser->getRows(true);

        foreach($rows as $index => $row) {
            if($index == 0) continue;
            if(empty($row)) continue;
            if(join('', $row) == '') continue;

            $formattedRow = $this->getFormatRow($rows[0], $row);

            // check email is empty,
            // then increase failed and continue
            if(empty($formattedRow->email)) {
                $job->failed = min($job->failed + 1, $job->total);
                $job->save();

                continue;
            }

            // check if email is already exist
            // if exist, update it
            // if not, create new one
            $user = $userInterface->byEmail($formattedRow->email);
            $mappings = unserialize($job->mappings);
            $data = $this->makeMappingRow($mappings, $formattedRow);
            if($user == null) {
                $result = $userInterface->create($data);
                if($result == false) {
                    $job->failed = min($job->failed + 1, $job->total);
                } else {
                    $job->created = min($job->created + 1, $job->total);
                }
            } else {
                $data['id'] = $user->id;
                $result = $userInterface->update($data);
                if($result == 'notSaved') {
                    $job->failed = min($job->failed + 1, $job->total);
                } else {
                    $job->updated = min($job->updated + 1, $job->total);
                }
            }
            $job->save();
        }

        $job->status = CompanyUserImportJob::STATUS_COMPLETED;
        $job->save();
    }

    private function getFormatRow($header, $row) {
        $result = new \stdClass();
        foreach($header as $index => $field) {
            if(isset($row[$index])) {
                $result->$field = $row[$index];
            } else {
                $result->$field = '';
            }
        }

        return $result;
    }

    private function makeMappingRow($mappings, $row) {
        $result = array();

        foreach($mappings as $mapping) {
            $field = $mapping['csvField'];
            $result[$mapping['dbField']] = $row->$field;
        }

        return $result;
    }
}
