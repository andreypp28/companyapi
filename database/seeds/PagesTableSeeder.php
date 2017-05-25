<?php


class PagesTableSeeder extends BaseTableSeeder
{
    protected $table = 'pages';

    public function run()
    {
        $this->deleteTable();

        factory(\WA\DataStore\Page\Page::class, 5)->create();
    }
}
