<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateDeviceUserTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'device_users';

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
            });

        Schema::table(
            $this->tableName,
            function ($table) {
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
                //$table->dropForeign('deviceId');
            });
        /*
        Schema::table(
            $this->tableName,
            function ( $table) {
                //$table->dropForeign('userId');
        });
        */

        $this->forceDropTable($this->tableName);
    }
}
