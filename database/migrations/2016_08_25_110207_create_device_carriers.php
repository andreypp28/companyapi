<?php

use Illuminate\Database\Migrations\Migration;

class CreateDeviceCarriers extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'device_carriers';

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
                $table->integer('carrierId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
                $table->foreign('carrierId')->references('id')->on('carriers')->onDelete('cascade');
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
