<?php

$factory->define(\WA\DataStore\Allocation\Allocation::class, function (\Faker\Generator $faker) {
    $currencies = ['USD'];
    $handsets = ['Apple iPhone 6 16GB Space Gray', 'Samsung Galaxy S4 - Black', 'Google Nexus 6 Xt1103'];
    $bill_months = ['2016-05-01', '2016-06-01', '2016-07-01', '2016-08-01'];
    $device_type = ['BB/PDA/iPhone', 'Data Card', 'Voice'];

    return [

        'billMonth' => $bill_months[array_rand($bill_months)],
        'mobileNumber' => $faker->e164PhoneNumber,

        'carrier' => $faker->biasedNumberBetween(1, 30),
        'currency' => $currencies[array_rand($currencies)],
        'handsetModel' => $handsets[array_rand($handsets)],

        'totalAllocatedCharge' => $faker->biasedNumberBetween(20, 150),
        'preAllocatedAmountDue' => $faker->biasedNumberBetween(20, 150),
        'otherAdjustments' => $faker->biasedNumberBetween(20, 12),
        'preAdjustedAccessCharge' => $faker->biasedNumberBetween(20, 100),

        // Service Plan Charges
        'adjustedAccessCharge' => $faker->biasedNumberBetween(20, 14),
        'bBCharge' => $faker->biasedNumberBetween(20, 30),
        'pDACharge' => $faker->biasedNumberBetween(20, 30),
        'iPhoneCharge' => $faker->biasedNumberBetween(20, 10),
        'featuresCharge' => $faker->biasedNumberBetween(20, 14),
        'dataCardCharge' => $faker->biasedNumberBetween(20, 14),
        'lDCanadaCharge' => $faker->biasedNumberBetween(20, 15),
        'uSAddOnPlanCharge' => $faker->biasedNumberBetween(20, 13),
        'uSLDAddOnPlanCharge' => $faker->biasedNumberBetween(20, 12),
        'uSDataRoamingCharge' => $faker->biasedNumberBetween(20, 13),
        'nightAndWeekendAddOnCharge' => $faker->biasedNumberBetween(20, 11),
        'minuteAddOnCharge' => $faker->biasedNumberBetween(20, 14),

        'servicePlanCharge' => $faker->biasedNumberBetween(20, 12),

        //Usage Charges
        'directConnectCharge' => $faker->biasedNumberBetween(20, 10),
        'textMessagingCharge' => $faker->biasedNumberBetween(20, 12),
        'dataCharge' => $faker->biasedNumberBetween(20, 10),
        'intlRoamingCharge' => $faker->biasedNumberBetween(20, 15),
        'intlLongDistanceCharge' => $faker->biasedNumberBetween(20, 12),
        'directoryAssistanceCharge' => $faker->biasedNumberBetween(20, 11),
        'callForwardingCharge' => $faker->biasedNumberBetween(20, 11),
        'airtimeCharge' => $faker->biasedNumberBetween(20, 12),

        // summation of usage charges
        'usageCharge' => $faker->biasedNumberBetween(20, 10),

        //Other Charges
        'equipmentCharge' => $faker->biasedNumberBetween(20, 24),
        'otherDiscountCharge' => $faker->biasedNumberBetween(20, 11),
        'taxesCharge' => $faker->biasedNumberBetween(20, 10),
        'thirdPartyCharge' => $faker->biasedNumberBetween(20, 13),

        //summation of other charges
        'otherCharge' => $faker->biasedNumberBetween(20, 10),

        //Fees
        'waFees' => $faker->biasedNumberBetween(12, 1),
        'lineFees' => $faker->biasedNumberBetween(23, 1),
        'mobilityFees' => $faker->biasedNumberBetween(1, 1),

        //summation of fees
        'feesCharge' => $faker->biasedNumberBetween(5, 15),

        //Last Upgrade
        'last_upgrade' => 'N/A',

        //Other usage and charges data
        'deviceType' => $device_type[array_rand($device_type)],
        'domesticUsageCharge' => $faker->biasedNumberBetween(5, 15),
        'domesticDataUsage' => $faker->biasedNumberBetween(50, 1500),
        'domesticVoiceUsage' => $faker->biasedNumberBetween(50, 1500),
        'domesticTextUsage' => $faker->biasedNumberBetween(50, 1500),
        'intlRoamUsageCharge' => $faker->biasedNumberBetween(50, 1500),
        'intlRoamDataUsage' => $faker->biasedNumberBetween(50, 1500),
        'intlRoamVoiceUsage' => $faker->biasedNumberBetween(50, 1500),
        'intlRoamTextUsage' => $faker->biasedNumberBetween(50, 1500),
        'intlLDUsageCharge' => $faker->biasedNumberBetween(50, 1500),
        'intlLDVoiceUsage' => $faker->biasedNumberBetween(50, 1500),
        'intlLDTextUsage' => $faker->biasedNumberBetween(50, 1500),
        'etfCharge' => $faker->biasedNumberBetween(50, 1500),
        'otherCarrierCharge' => $faker->biasedNumberBetween(50, 1500),
        'deviceEsnImei' => $faker->biasedNumberBetween(50, 1500),
        'deviceSim' => $faker->biasedNumberBetween(50, 1500),



        'userId' => 1,

        'companyId' => 1,

    ];
});
