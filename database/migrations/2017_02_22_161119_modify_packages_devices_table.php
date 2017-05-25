<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPackagesDevicesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'package_devices';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('package_devices_deviceid_foreign');
                $table  ->dropColumn('deviceId');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->integer('deviceVariationId')->unsigned()->nullable();
                $table->foreign('deviceVariationId')->references('id')->on('device_variations')->onDelete('cascade');
            }
        );
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
                $table->dropForeign('package_devices_devicevariationid_foreign');
                $table->dropColumn('deviceVariationId');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->integer('deviceId')->unsigned()->nullable();
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
            }
        );
    }
}
