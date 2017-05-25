<?php

class UdlsTableSeeder extends BaseTableSeeder
{
    protected $table = 'udls';

    public function run()
    {
        $this->deleteTable();

        $data = [
            [
                'companyId' => 1,
                'name' => 'Position',
                'legacyUdlField' => null,
                'inputType' => 'string'
            ],
            [
                'companyId' => 1,
                'name' => 'Division',
                'legacyUdlField' => null,
                'inputType' => 'number'
            ],
            [
                'companyId' => 1,
                'name' => 'Cost Center',
                'legacyUdlField' => null,
                'inputType' => 'string'
            ],
            [
                'companyId' => 2,
                'name' => 'Sector',
                'legacyUdlField' => null,
                'inputType' => 'string'
            ],
            [
                'companyId' => 2,
                'name' => 'Vehicle',
                'legacyUdlField' => null,
                'inputType' => 'string'
            ],
            [
                'companyId' => 2,
                'name' => 'Radio',
                'legacyUdlField' => null,
                'inputType' => 'string'
            ],
            [
                'companyId' => 2,
                'name' => 'Division',
                'legacyUdlField' => null,
                'inputType' => 'string'
            ],
            [
                'companyId' => 3,
                'name' => 'Position',
                'legacyUdlField' => null,
                'inputType' => 'string'
            ],
            [
                'companyId' => 3,
                'name' => 'Sector',
                'legacyUdlField' => null,
                'inputType' => 'string'
            ],
            [
                'companyId' => 3,
                'name' => 'Vehicle',
                'legacyUdlField' => null,
                'inputType' => 'string'
            ],
            [
                'companyId' => 3,
                'name' => 'Cost Center',
                'legacyUdlField' => null,
                'inputType' => 'string'
            ]
        ];

        $this->loadTable($data);
    }
}
