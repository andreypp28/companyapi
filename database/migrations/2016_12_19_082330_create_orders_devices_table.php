<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersDevicesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'order_devices';
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists($this->tableName);

        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('orderId')->unsigned();
                $table->integer('deviceId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('orderId')->references('id')->on('orders')->onDelete('cascade');
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
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
                $table->dropForeign('order_devices_orderid_foreign');
                $table->dropColumn('orderId');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('order_devices_deviceid_foreign');
                $table->dropColumn('deviceId');
        });                

        $this->forceDropTable($this->tableName);
    }
}