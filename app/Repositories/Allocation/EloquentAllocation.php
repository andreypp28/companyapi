<?php

namespace WA\Repositories\Allocation;

use WA\Repositories\AbstractRepository;

use Log;
use Carbon;

class EloquentAllocation extends AbstractRepository implements AllocationInterface
{
    /**
     * Get current charges of user for current billing month.
     *
     * @param $email
     *
     * @return mixed
     */
    public function getCurrentCharges($email)
    {
        $start =  date('Y-m-d 00:00:00', strtotime('first day of this month'));

        $charges = $this->model->where('User Email', $email)->where('Bill Month', $start)->get();

        return $charges;
    }

    /**
     * Get Allocations Transformer.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return $this->model->getTransformer();
    }
}
