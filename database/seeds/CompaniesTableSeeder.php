<?php

class CompaniesTableSeeder extends BaseTableSeeder {
	protected $table = 'companies';

	public function run() {
		$this->deleteTable();
		factory(\WA\DataStore\Company\Company::class, 19)->create();

		$bill_months = ['2016-05-01', '2016-06-01', '2016-07-01', '2016-08-01'];

		$dataCompany = [
            'name' => 'Testing Company',
	        'label' => 'testing_company',
	        'active' => 1,
	        'isCensus'=> 0,
	        'assetPath' => 'asset_path',
	        'created_at' => null,
	        'updated_at' => null,
	        'currentBillMonth' => $bill_months[array_rand($bill_months)],
	        'shortName' => 'test',
	        'defaultLocation' => 1,
        ];

        $this->loadTable($dataCompany);
	}
}
