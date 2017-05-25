<?php

namespace WA\Repositories\JobStatus;

interface JobStatusInterface
{
    /**
     * Get the job id by it's name.
     *
     * @param $name
     *
     * @return int id | null
     */
    public function idByName($name);

    /**
     * Get the status name by its ID.
     *
     * @param $id
     *
     * @return string $name | null
     */
    public function nameById($id);
}
