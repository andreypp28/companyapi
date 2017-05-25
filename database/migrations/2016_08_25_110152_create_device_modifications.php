<?php

use Illuminate\Database\Migrations\Migration;

class CreateDeviceModifications extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'device_modifications';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('deviceId')->unsigned();
                $table->integer('modificationId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
                $table->foreign('modificationId')->references('id')->on('modifications')->onDelete('cascade');
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
