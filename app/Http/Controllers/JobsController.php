<?php

namespace WA\Http\Controllers;

use WA\Repositories\CompanyCurrentBillMonth\CompanyCurrentBillMonthInterface;

/**
 * Class jobsController
 *
 * @package WA\Http\Controllers
 */
class jobsController extends FilteredApiController
{
    /**
     * @var CompanyCurrentBillMonthInterface
     */
    protected $currentBillMonth;

    public function __construct(CompanyCurrentBillMonthInterface $currentBillMonth)
    {
        $this->currentBillMonth = $currentBillMonth;
    }

    /**
     * Update current bill month for companies.
     *
     *
     * @return \Dingo\Api\Http\Response
     */
    public function updateBillingMonths()
    {

        //Get the most recent billing month for every company per carrier
        $query = <<<BLOCK

        SELECT c.id as companyId, cr.id as carrierId, Max(ac.billMonth) AS currentBillMonth
FROM allocations ac
LEFT JOIN carriers cr on cr.id = ac.carrier  COLLATE utf8_unicode_ci
LEFT JOIN companies c on c.id = ac.companyId
WHERE cr.id IS NOT NULL
GROUP BY ac.carrier, ac.companyId
ORDER BY ac.companyId
BLOCK;

        $currentBillMonths = \DB::select($query);

        //Update the current bill month table with the latest date
        foreach ($currentBillMonths as $billMonth) {
            $companyId = isset($billMonth->{'companyId'}) ? $billMonth->{'companyId'} : null;
            $carrierId = isset($billMonth->{'carrierId'}) ? $billMonth->{'carrierId'} : null;
            $billMonth = isset($billMonth->{'currentBillMonth'}) ? $billMonth->{'currentBillMonth'} : null;


            //select from current bill month table by company id and carrier id
            $currentBillMonth = $this->currentBillMonth->byCompanyIdAndCarrierId($companyId, $carrierId);


            //If exists, update using id
            if (!empty($currentBillMonth)) {
                $data = [
                    'id' => $currentBillMonth['id'],
                    'billMonth' => $billMonth ? $billMonth : null,
                ];

                //$id = $currentBillMonth['id'];
                //echo "Updated for Id $id <br />";

                $this->currentBillMonth->update($data);

            } else {
                //If not exists, add an entry
                $billData = [
                    'companyId' => $companyId,
                    'carrierId' => $carrierId,
                    'billMonth' => $billMonth
                ];

                $this->currentBillMonth->create($billData);
                //echo"bill month inserted for company $companyId and carrier $carrierId <br />";

            }

        }

        if (!empty($currentBillMonths)) {
            $message['message']['put'] = "Company Current Bill Months Updated";
            return response()->json($message)->setStatusCode(200);

        } else {
            $message['message']['put'] = "Company Current Bill Months Not Updated";
            return response()->json($message)->setStatusCode(400);

        }
    }
}