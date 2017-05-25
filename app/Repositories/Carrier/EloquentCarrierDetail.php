<?php

namespace WA\Repositories\Carrier;

use WA\Repositories\AbstractRepository;

class EloquentCarrierDetail extends AbstractRepository implements CarrierDetailInterface
{
    /**
     * Get the carrier details by ID.
     *
     * @param int    $companyId
     * @param string $billMonth as YYYY-MM-DD
     * @param int    $carrierId
     *
     * @return object object of carrier details
     */
    public function byCompany($companyId, $carrierId, $billMonth)
    {
        $response = $this->model
            ->where('companyId', $companyId)
            ->where('carrierId', $carrierId)
            ->where('billMonth', $billMonth);

        return $response->get();
    }
}
