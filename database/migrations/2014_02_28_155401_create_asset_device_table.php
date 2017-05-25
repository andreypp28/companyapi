<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateAssetDeviceTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'asset_devices';

    protected $foreignColumns = [
        'assetId',
        'deviceId',
    ];

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->timestamps();
                $table->integer('assetId')->unsigned();
                $table->integer('deviceId')->unsigned();
            });

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('assetId')->references('id')->on('assets')->onDelete('cascade');
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
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
                //$table->dropForeign('assetId');
                //$table->dropForeign('deviceId');
            });

        $this->forceDropTable($this->tableName);
    }
}
