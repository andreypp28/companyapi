<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteDeviceCarriersTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'device_carriers';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('device_carriers_deviceid_foreign');
                $table->dropColumn('deviceId');
        });
        
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('device_carriers_carrierid_foreign');
                $table->dropColumn('carrierId');
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
                $table->integer('deviceId')->unsigned();
                $table->integer('carrierId')->unsigned();
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
}
