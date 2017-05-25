<?php

namespace WA\Repositories\CompanyCurrentBillMonth;

use WA\Repositories\RepositoryInterface;

interface CompanyCurrentBillMonthInterface  extends RepositoryInterface
{

    /**
     * CurrentBillMonth by the id.
     *
     * @param int $id
     *
     * @return object object of the CurrentBillMonth information
     */
    public function byId($id);

    /**
     * Get CurrentBillMonths Transformer.
     *
     * @return mixed
     */
    public function getTransformer();

    /**
     * Get by company id and carrier id
     *
     * @param $companyId
     * @param $carrierId
     * @return mixed
     */
    public function byCompanyIdAndCarrierId($companyId, $carrierId);

    /**
     * Create new entry in current bill month table.
     *
     * @param array $data
     *
     * @return bool|static
     */
    public function create(array $data);

    /**
     * Update current bill month for a company/carrier
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data);


}
