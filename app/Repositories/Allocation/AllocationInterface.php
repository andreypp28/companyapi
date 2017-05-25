<?php

namespace WA\Repositories\Allocation;

use WA\Repositories\RepositoryInterface;

/**
 * Interface AllocationInterface.
 */
interface AllocationInterface extends RepositoryInterface
{
    /**
     * Get Allocations Transformer.
     *
     * @return mixed
     */
    public function getTransformer();
}
