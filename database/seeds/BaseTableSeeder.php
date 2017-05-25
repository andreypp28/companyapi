<?php

use Illuminate\Database\Seeder;

class BaseTableSeeder extends Seeder
{
    protected $data;

    protected $table;

    // Toggle this to seed random data or not
    protected $seedRandom = false;

    public function deleteTable()
    {
        return DB::table($this->table)->delete();
    }

    public function run()
    {
        $this->setupDb();

        $this->loadTable();

        $this->teardownDb();
    }

    public function setupDb()
    {
        if (\DB::connection() instanceof Illuminate\Database\MySqlConnection) {
            \DB::connection()->disableQueryLog();
            \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            \DB::statement('SET UNIQUE_CHECKS = 0');
        }
    }

    public function loadTable($data = false)
    {
        if (is_array($data)) {
            $loadData = $data;
        } else {
            $loadData = $this->data;
        }

        return DB::table($this->table)->insert($loadData);
    }

    public function teardownDb()
    {
        if (\DB::connection() instanceof Illuminate\Database\MySqlConnection) {
            DB::raw('SET FOREIGN_KEY_CHECKS = 1');
            DB::raw('SET UNIQUE_CHECKS = 1');
        }
    }

    public function truncateTable()
    {
        return DB::table($this->table)->truncate();
    }
}
