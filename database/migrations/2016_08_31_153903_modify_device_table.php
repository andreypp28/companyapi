<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class ModifyDeviceTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'devices';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table($this->tableName, function ($table) {
            $table->dropForeign('devices_carrierid_foreign');
            $table->dropColumn('carrierId');
        });

        Schema::table($this->tableName, function ($table) {
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table($this->tableName, function ($table) {
            $table->integer('carrierId')->unsigned()->nullable();
            $table->foreign('carrierId')->references('id')->on('carriers');
            $table->string('image')->default('');
        });
    }
}
