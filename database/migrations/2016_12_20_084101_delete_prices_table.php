<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeletePricesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'prices';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->forceDropTable($this->tableName);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
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
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('capacityId')->references('id')->on('modifications')->onDelete('cascade');
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('styleId')->references('id')->on('modifications')->onDelete('cascade');
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('carrierId')->references('id')->on('carriers')->onDelete('cascade');
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('companyId')->references('id')->on('companies')->onDelete('cascade');
            }
        );

        $this->forceDropTable($this->tableName);
    }
}
