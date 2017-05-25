<?php

namespace WA\DataStore\Carrier;

/**
 * Class CarrierDetailTransformer.
 */
class CarrierDetailTransformer
{
    /**
     * @param CarrierDetail $details
     *
     * @return array
     */
    public function transform(CarrierDetail $details)
    {
        return [
            'id' => $details->company->id,
            'carrier' => $details->carrier->presentation,
            'billMonth' => $details->billMonth,
            'totalSpend' => $details->preAdjustedTotalCharges,
            'totalLines' => $details->lineCount,
        ];
    }
}
