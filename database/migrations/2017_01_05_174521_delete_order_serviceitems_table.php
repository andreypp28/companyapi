<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteOrderServiceitemsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'order_serviceitems';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('order_serviceitems_orderid_foreign');
                $table->dropColumn('orderId');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('order_serviceitems_serviceitemid_foreign');
                $table->dropColumn('serviceItemId');
        });

        $this->forceDropTable($this->tableName);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);

        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('orderId')->unsigned();
                $table->integer('serviceItemId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('orderId')->references('id')->on('orders')->onDelete('cascade');
                $table->foreign('serviceItemId')->references('id')->on('service_items')->onDelete('cascade');
            }
        );
    }
}
