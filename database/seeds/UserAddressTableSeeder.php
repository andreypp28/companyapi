<?php

class UserAddressTableSeeder extends BaseTableSeeder
{
    protected $table = 'user_address';

    public function run()
    {
        $this->deleteTable();
        $i = 1;
        while ($i < 25) {
            $data = [
                [
                    'userId' => 1,
                    'addressId' => $i
                ]
            ];

            $this->loadTable($data);
            $i++;
        }        
    }
}