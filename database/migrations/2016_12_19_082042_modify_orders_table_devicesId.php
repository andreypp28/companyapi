<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class ModifyOrdersTableDevicesId extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'orders';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('orders_deviceid_foreign');
                $table->dropColumn('deviceId');
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
                $table->integer('deviceId')->unsigned()->nullable();
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->foreign('deviceId')->references('id')->on('devices');
        });
    }
}