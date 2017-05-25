<?php

use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'prices';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('priceRetail');
                $table->integer('price1');
                $table->integer('price2');
                $table->integer('priceOwn');
                $table->integer('deviceId')->unsigned();
                $table->integer('capacityId')->unsigned();
                $table->integer('styleId')->unsigned();
                $table->integer('carrierId')->unsigned();
                $table->integer('companyId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
                $table->foreign('capacityId')->references('id')->on('modifications')->onDelete('cascade');
                $table->foreign('styleId')->references('id')->on('modifications')->onDelete('cascade');
                $table->foreign('carrierId')->references('id')->on('carriers')->onDelete('cascade');
                $table->foreign('companyId')->references('id')->on('companies')->onDelete('cascade');
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
                //$table->dropForeign('styleId');
                //$table->dropForeign('capacityId');
                //$table->dropForeign('carrierId');
                //$table->dropForeign('providerId');
            });

        $this->forceDropTable($this->tableName);
    }
}
