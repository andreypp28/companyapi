<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateAssetTypesTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'asset_types';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->string('description');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->forceDropTable($this->tableName);
    }
}
