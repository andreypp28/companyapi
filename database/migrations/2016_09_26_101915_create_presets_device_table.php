<?php

use Illuminate\Database\Migrations\Migration;

class CreatePresetsDeviceTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'preset_devices';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('presetId')->unsigned();
                $table->integer('deviceId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('presetId')->references('id')->on('presets')->onDelete('cascade');
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
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
