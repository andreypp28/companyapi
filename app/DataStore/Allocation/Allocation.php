<?php

namespace WA\DataStore\Allocation;

use WA\DataStore\BaseDataStore;

/**
 * Class Allocations.
 *
 * @property-read \WA\DataStore\Company\Company $companies
 * @property-read \WA\DataStore\User\User $users
 * @property-read \WA\DataStore\User\User $carriers
 * @mixin \Eloquent
 */
class Allocation extends BaseDataStore
{
    protected $table = 'allocations';

    public $timestamps = false;

    protected $fillable = ['userId', 'companyId', 'billMonth', 'mobileNumber', 'carrier', 'currency', 'handsetModel', 'totalAllocatedCharge', 'preAllocatedAmountDue', 'otherAdjustments', 'preAdjustedAccessCharge', 'adjustedAccessCharge', 'bBCharge', 'pDACharge', 'iPhoneCharge', 'featuresCharge', 'dataCardCharge', 'lDCanadaCharge', 'uSAddOnPlanCharge', 'uSLDAddOnPlanCharge', 'uSDataRoamingCharge', 'nightAndWeekendAddOnCharge', 'minuteAddOnCharge', 'servicePlanCharge', 'directConnectCharge', 'textMessagingCharge', 'dataCharge', 'intlRoamingCharge', 'intlLongDistanceCharge', 'directoryAssistanceCharge', 'callForwardingCharge', 'airtimeCharge', 'usageCharge', 'equipmentCharge', 'otherDiscountCharge', 'taxesCharge', 'thirdPartyCharge', 'otherCharge', 'waFees', 'lineFees', 'mobilityFees', 'feesCharge', 'last_upgrade', 'otherAdjustments',
        'featuresCharge', 'deviceType', 'domesticUsageCharge', 'domesticDataUsage', 'domesticVoiceUsage', 'domesticTextUsage', 'intlRoamUsageCharge',
    'intlRoamDataUsage', 'intlRoamVoiceUsage', 'intlRoamTextUsage', 'intlLDUsageCharge', 'intlLDVoiceUsage', 'intlLDTextUsage', 'etfCharge', 'otherCarrierCharge', 'deviceEsnImei','deviceSim'];

    public function companies()
    {
        return $this->belongsTo('WA\DataStore\Company\Company', 'companyId');
    }

    public function users()
    {
        return $this->belongsTo('WA\DataStore\User\User', 'userId');
    }

    public function carriers()
    {
        return $this->belongsTo('WA\DataStore\Carrier\Carrier', 'carrier');
    }

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new AllocationTransformer();
    }
}
