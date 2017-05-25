<?php

/**
 * ServiceItemsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class ServiceItemsTableSeeder extends BaseTableSeeder
{
    protected $table = 'service_items';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $i = 1;
        while ($i < 901) {

            $data = [
                [   
                    'serviceId'     => $i,
                    'category'      => 'voice',
                    'description'   => '',
                    'value'         => rand(200,500),
                    'unit'          => 'minutes',
                    'cost'          => rand(10,15),
                    'domain'        => 'domestic',
                ],
                [
                    'serviceId'     => $i,
                    'category'      => 'data',
                    'description'   => '',
                    'value'         => rand(2,5),
                    'unit'          => 'Gb',
                    'cost'          => rand(10,20),
                    'domain'        => 'domestic',
                ],
                [
                    'serviceId'     => $i,
                    'category'      => 'messaging',
                    'description'   => '',
                    'value'         => rand(100,300),
                    'unit'          => 'messages',
                    'cost'          => rand(5,10),
                    'domain'        => 'domestic',
                ],
                [   
                    'serviceId'     => $i,
                    'category'      => 'voice',
                    'description'   => '',
                    'value'         => rand(200,500),
                    'unit'          => 'minutes',
                    'cost'          => rand(15,25),
                    'domain'        => 'international',
                ],
                [
                    'serviceId'     => $i,
                    'category'      => 'data',
                    'description'   => '',
                    'value'         => rand(2,5),
                    'unit'          => 'Gb',
                    'cost'          => rand(15,30),
                    'domain'        => 'international',
                ],
                [
                    'serviceId'     => $i,
                    'category'      => 'messaging',
                    'description'   => '',
                    'value'         => rand(100,300),
                    'unit'          => 'messages',
                    'cost'          => rand(10,20),
                    'domain'        => 'international',
                ]
            ];
            $i++;

            $this->loadTable($data);
        }
    }
}
