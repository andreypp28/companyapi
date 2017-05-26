<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use WA\DataStore\Allocation\Allocation;

class AllocationsApiTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic functional test for Allocations.
     *
     * @AD: last_update field needs to be revised in table and Allocation.
     */
    public function testGetAllocations()
    {
        $allocation = factory(\WA\DataStore\Allocation\Allocation::class)->create();
        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        $allocation->carriers()->associate($carrier1);
        $allocation->save();

        $this->json('GET', '/allocations/')
            ->seeJsonStructure(
            [
                'data' => [
                    0 => [
                        'type',
                        'id',
                        'attributes' => [
                            'bill_month',
                            'carrier',
                            'mobile_number',
                            'currency',
                            'device',
                            'allocated_charge',
                            'service_plan_charge',
                            'usage_charge',
                            'other_charge',
                            'fees_charge',
                            'last_upgrade',
                            'messaging_charge',
                            'taxes_charge',
                            'equipment_charge',
                            'device_type',
                            'domestic_usage_charge',
                            'domestic_data_usage',
                            'domestic_voice_usage',
                            'domestic_text_usage',
                            'intl_roam_usage_charge',
                            'int_roam_data_usage',
                            'intl_roam_voice_usage',
                            'intl_roam_text_usage',
                            'intl_ld_usage_charge',
                            'intl_ld_voice_usage',
                            'intl_ld_text_usage',
                            'etf_charge',
                            'other_carrier_charge',
                            'device_esn_imei',
                            'device_sim'

                        ],
                        'links' => [
                            'self',
                        ],
                    ],
                ],
            ]);
    }

    public function testGetAllocationById()
    {
        $allocation = factory(\WA\DataStore\Allocation\Allocation::class)->create();
        $carrier1 = factory(\WA\DataStore\Carrier\Carrier::class)->create();
        $allocation->carriers()->associate($carrier1);
        $allocation->save();

        $this->json('GET', '/allocations/'.$allocation->id)
            ->seeJsonStructure(
                [
                    'data' => [
                            'type',
                            'id',
                            'attributes' => [
                                'bill_month',
                                'carrier',
                                'mobile_number',
                                'currency',
                                'device',
                                'allocated_charge',
                                'service_plan_charge',
                                'usage_charge',
                                'other_charge',
                                'fees_charge',
                                'last_upgrade',
                                'messaging_charge',
                                'taxes_charge',
                                'equipment_charge',
                                'device_type',
                                'domestic_usage_charge',
                                'domestic_data_usage',
                                'domestic_voice_usage',
                                'domestic_text_usage',
                                'intl_roam_usage_charge',
                                'int_roam_data_usage',
                                'intl_roam_voice_usage',
                                'intl_roam_text_usage',
                                'intl_ld_usage_charge',
                                'intl_ld_voice_usage',
                                'intl_ld_text_usage',
                                'etf_charge',
                                'other_carrier_charge',
                                'device_esn_imei',
                                'device_sim'

                            ],
                            'links' => [
                                'self',
                            ],
                    ],
                ]);
    }
}
