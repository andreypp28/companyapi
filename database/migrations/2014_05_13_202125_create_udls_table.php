<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateUdlsTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'udls';
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('companyId', false, true);
                $table->string('name');
                $table->string('label');
                $table->string('legacyUdlField', 50)->nullable();
                $table->unique(['companyId', 'name']);

                $table->nullableTimestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->forceDropTable($this->tableName);
    }
}
