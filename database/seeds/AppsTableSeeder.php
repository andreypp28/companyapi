<?php

/**
 * AppsTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class AppsTableSeeder extends BaseTableSeeder
{
    protected $table = 'apps';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'type' => 'marketing',
                'image' => 'imagegoogle',
                'description' => 'imagegoogleapp',
            ],
            [
                'type' => 'games',
                'image' => 'ageofempires',
                'description' => 'ageofempiresgameapp',
            ],
            [
                'type' => 'comercial',
                'image' => 'imagecomercial',
                'description' => 'imagecomercialapp',
            ],
            [
                'type' => 'marketing',
                'image' => 'imageseosem',
                'description' => 'imageseosemapp',
            ],
        ];

        $this->loadTable($data);
    }
}
