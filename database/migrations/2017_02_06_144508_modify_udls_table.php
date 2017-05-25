<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUdlsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'udls';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('label');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->string('inputType')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('inputType');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->string('label')->nullable();
        });
    }
}
