<?php

namespace WA\Helpers;

use DB;

/**
 * Class DBSnapshot.
 */
class DBSnapshot
{
    protected $connectionName;

    /**
     * @param $tableName
     * @param null $file
     * @param null $query
     *
     * @return bool|string
     */
    public function dumpTable($tableName, $file = null, $query = null)
    {
        try {
            $this->setUpSnapshotCxn();
            $db = DB::connection()->getDatabaseName();

            $path = base_path().DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.
                'seeds'.DIRECTORY_SEPARATOR.'csv'.DIRECTORY_SEPARATOR;

            $fileName = $tableName.'_'.date('Ymd').'.csv';

            $fieldTerminator = ',';
            if ($tableName == 'raw_data_maps') {
                $fieldTerminator = '|';
            }

            $tmpPath = DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$fileName;
            $permPath = $file ?: $path.$fileName;

            if (file_exists($permPath)) {
                \Alert::error('File already exists.  Delete it first, because we won\'t clobber it.');

                return false;
            }

            $columns = DB::select(DB::raw("SHOW COLUMNS FROM $tableName"));

            $fields = '';
            $fileHeader = '';

            $t = 0;
            $colCount = count($columns);

            foreach ($columns as $c) {
                if ($t == $colCount - 1) {
                    $fileHeader .= '"'.$c->Field.'" ';
                    $fields .= 'COALESCE(`'.$c->Field.'`, "") ';
                } else {
                    $fileHeader .= '"'.$c->Field.'", ';
                    $fields .= 'COALESCE(`'.$c->Field.'`, ""), ';
                }

                ++$t;
            }

            $query = "SELECT $fileHeader UNION ALL SELECT $fields INTO OUTFILE '$tmpPath' FIELDS TERMINATED BY '$fieldTerminator' ENCLOSED BY '\"' LINES TERMINATED BY '\\n' FROM $tableName";

            DB::connection()->disableQueryLog();
            DB::connection()->getPdo()->quote($fileName);
            DB::connection()->getPdo()->exec($query);
            exec("mv $tmpPath $permPath");

            return $fileName;
        } catch (\Exception $e) {
            \Alert::error('Something bad happened: '.$e->getMessage());

            return false;
        }
    }

    protected function setUpSnapshotCxn()
    {
        $cxn = array(
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => 'information_schema',
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        );

        \Config::set('database.connections.snapshot', $cxn);
    }

    /**
     * @param null $db
     *
     * @return mixed
     */
    public function getTables(
        $db = null
    ) {
        $this->setUpSnapshotCxn();

        $db = $db ?: DB::connection()->getDatabaseName();

        $tables = DB::connection('snapshot')
            ->table('tables')
            ->select('TABLE_NAME AS tableName')
            ->where('TABLE_SCHEMA', $db)
            ->get('TABLE_NAME');

        return $tables;
    }
}
