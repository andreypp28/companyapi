<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteAssetDevicesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'asset_devices';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('asset_devices_assetid_foreign');
                $table->dropColumn('assetId');
        });
        
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('asset_devices_deviceid_foreign');
                $table->dropColumn('deviceId');
        });

        $this->forceDropTable($this->tableName);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('assetId')->unsigned();
                $table->integer('deviceId')->unsigned();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('assetId')->references('id')->on('assets')->onDelete('cascade');
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
            }
        );
    }
}
