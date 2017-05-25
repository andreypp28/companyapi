<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceVariationsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'device_variations';
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
                $table->integer('priceRetail');
                $table->integer('price1');
                $table->integer('price2');
                $table->integer('priceOwn');
                $table->integer('deviceId')->unsigned();
                $table->integer('carrierId')->unsigned();
                $table->integer('companyId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
                $table->foreign('carrierId')->references('id')->on('carriers')->onDelete('cascade');
                $table->foreign('companyId')->references('id')->on('companies')->onDelete('cascade');
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
                $table->dropForeign('device_variations_deviceId_foreign');
                $table->dropColumn('deviceId');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('device_variations_carrierId_foreign');
                $table->dropColumn('carrierId');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('device_variations_companyId_foreign');
                $table->dropColumn('companyId');
        });

        $this->forceDropTable($this->tableName);
    }
}