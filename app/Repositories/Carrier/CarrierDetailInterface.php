<?php

namespace WA\Repositories\Carrier;

interface CarrierDetailInterface
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
    public function byCompany($companyId, $carrierId, $billMonth);
}
