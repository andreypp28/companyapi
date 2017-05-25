<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDeviceTypesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'device_types';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->string('name')->nullable();
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('make');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('model');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('class');
        });
        
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('deviceOS');
        });
        
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('description');
        });
        
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('image');
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
                $table->dropColumn('name');
        });

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->string('make')->nullable();
                $table->string('model')->nullable();
                $table->string('class')->nullable();
                $table->string('deviceOS')->nullable();
                $table->string('description')->nullable();
                $table->string('image')->nullable();
            });
    }
}