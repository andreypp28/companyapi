<?php

namespace WA\Database\Command;

use DB;
use Illuminate\Database\Schema\Blueprint;
use Schema;

trait TablesRelationsAndIndexes
{
    /**
     * Forces a table to drop independent of constraints
     * (should really only be used in a mass migration context).
     *
     * @param $tableName
     */
    public function forceDropTable($tableName)
    {
        switch (DB::getDriverName()) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = OFF');
                break;
        }

        Schema::dropIfExists($tableName);

        switch (DB::getDriverName()) {
            case 'mysql':
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                break;
            case 'sqlite':
                DB::statement('PRAGMA foreign_keys = ON');
                break;
        }
    }

    /**
     * Add foreign keys to columns, keys are automatically indexed.
     *
     * @param $table
     * @param array  $relationships
     * @param string $idColumn
     */
    protected function addForeign($table, array $relationships, $idColumn = 'id')
    {
        if (!Schema::hasTable($table)) {
            exit("Table $table does not exist, cannot continue!");
        }

        Schema::table(
            $table,
            function (Blueprint $t) use ($relationships, $idColumn, $table) {
                foreach ($relationships as $key => $refTable) {
                    $t->foreign($key)
                        ->references($idColumn)
                        ->on($refTable)
                        ->onDelete('cascade')
                        ->onUpdate('cascade');

                    $this->addIndex($table, $key);
                }
            }
        );
    }

    /**
     * Add indexes to columns.
     *
     * @param $table
     * @param string | array $columns
     */
    protected function addIndex($table, $columns)
    {
        if (!is_array($columns)) {
            $key[] = $columns;
        } else {
            $key = $columns;
        }

        Schema::table(
            $table,
            function (Blueprint $t) use ($key) {
                foreach ($key as $column) {
                    $t->index($column);
                }
            }
        );
    }

    /**
     * Add a compound index to a table.
     *
     * @param $table
     * @param $columns
     *
     * @return bool
     */
    protected function addCompoundIndex($table, $columns)
    {
        if (!is_array($columns)) {
            return false;
        }
        Schema::table(
            $table,
            function (Blueprint $t) use ($columns) {
                $t->index($columns);
            }
        );
    }

    /**
     * @param $table
     * @param $columns
     */
    protected function addUnique($table, $columns)
    {
        Schema::table(
            $table,
            function (Blueprint $t) use ($columns) {
                $t->unique($columns);
            }
        );
    }

    /**
     * Drop all indexes on table, automatically removes added indexes.
     *
     * @param $table
     * @param array $relationships
     */
    protected function dropForeignKeys($table, array $relationships)
    {
        if (!Schema::hasTable($table)) {
            echo '';
            exit("Table $table does not exist, cannot continue! \\n");
        }

        $tableName = snake_case($table);

        Schema::table(
            $table,
            function (Blueprint $t) use ($relationships, $tableName) {
                foreach ($relationships as $column => $opt) {
                    if ($opt === 'nullable') {
                        $columnName = strtolower($column);
                    } else {
                        $columnName = strtolower($opt);
                    }
                    $foreignKey = $tableName.'_'.$columnName.'_'.'foreign';
                    $indexName = $tableName.'_'.$columnName.'_'.'index';

                    $t->dropForeign($foreignKey);
                    $t->dropIndex($indexName);
                }
            }
        );
    }

    /**
     * Drop all foreign keys on tables.
     *
     * @param $tableName
     * @param string | array $indexes
     */
    protected function dropIndexes($tableName, $indexes)
    {
        if (!is_array($indexes)) {
            $keys[] = $indexes;
        } else {
            $keys = $indexes;
        }

        Schema::table(
            $tableName,
            function (Blueprint $t) use ($keys, $tableName) {
                foreach ($keys as $column => $opt) {
                    if ($opt === 'nullable') {
                        $columnName = strtolower($column);
                    } else {
                        $columnName = strtolower($opt);
                    }
                    $index = $tableName.'_'.$columnName.'_'.'index';

                    $t->dropIndex($index);
                }
            }
        );
    }

    /**
     * Includes foreign keys relationships to this table.
     */
    protected function includeForeign(Blueprint $table, $keys)
    {
        if (!is_array($keys)) {
            $foreign[] = $keys;
        } else {
            $foreign = $keys;
        }

        foreach ($foreign as $column => $opt) {
            if ($opt === 'nullable') {
                $table->integer($column, false, true)->nullable();
            } else {
                $table->integer($opt, false, true);
            }
        }
    }
}
