<?php

/**
 * RequestsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class RequestsTableSeeder extends BaseTableSeeder
{
    protected $table = 'requests';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'name' => 'Request1',
                'description' => 'descriptionrequest1',
                'type' => 'typerequest1',
            ],
            [
                'name' => 'Request2',
                'description' => 'descriptionrequest2',
                'type' => 'typerequest2',
            ],
            [
                'name' => 'Request3',
                'description' => 'descriptionrequest3',
                'type' => 'typerequest3',
            ],
            [
                'name' => 'Request4',
                'description' => 'descriptionrequest4',
                'type' => 'typerequest4',
            ],
        ];

        $this->loadTable($data);
    }
}
