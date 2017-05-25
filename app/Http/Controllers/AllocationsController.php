<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use WA\Repositories\Allocation\AllocationInterface;

/**
 * Allocations resource.
 *
 * @Resource("Allocations", uri="/allocations")
 */
class AllocationsController extends FilteredApiController
{
    /**
     * @var AllocationInterface
     */
    protected $allocations;

    /**
     * AllocationsController constructor.
     *
     * @param AllocationInterface $allocations
     * @param Request $request
     */
    public function __construct(AllocationInterface $allocations, Request $request)
    {
        parent::__construct($allocations, $request);
        $this->allocations = $allocations;
    }
}
