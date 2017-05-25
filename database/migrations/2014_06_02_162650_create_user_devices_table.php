<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateUserDevicesTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'user_devices';

    protected $foreignColumns = [
        'userId',
        'deviceId',
    ];

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('userId')->unsigned();
                $table->integer('deviceId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('userId')->references('id')->on('users');
                $table->foreign('deviceId')->references('id')->on('devices');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table(
            $this->tableName,
            function ($table) {
                //$table->dropForeign('userId');
                //$table->dropForeign('deviceId');
            });
        $this->forceDropTable($this->tableName);
    }
}
