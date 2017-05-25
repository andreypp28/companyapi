<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateDevicesTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'devices';

    protected $foreignColumns = [
        'deviceTypeId',
        'statusId' => 'nullable',
        'carrierId' => 'nullable',
        'syncId' => 'nullable',
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
                $table->string('identification')->unique();
                $table->string('image');
                $table->string('name');
                $table->string('properties');
                $table->integer('externalId')->unique()->nullable();
                $table->integer('deviceTypeId')->unsigned();
                $table->integer('statusId')->unsigned()->nullable();
                $table->integer('carrierId')->unsigned()->nullable();
                $table->integer('syncId')->unsigned()->nullable();

                $table->nullableTimestamps();
            });

        Schema::table(
            $this->tableName,
            function ($table) {
                //¿¿ $table->foreign('statusId')->references('id')->on('companies'); ??
                $table->foreign('carrierId')->references('id')->on('carriers');
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
                //$table->dropForeign('carrierId');
            });

        /*
        Schema::table(
            $this->tableName,
            function ($table) {
                //$table->dropForeign('deviceTypeId');
                //$table->dropForeign('syncId');
        });

        Schema::table(
            'device_users',
            function ($table) {
                //$table->dropForeign('deviceId');
        });

        Schema::table(
            'asset_devices',
            function ($table) {
                //$table->dropForeign('deviceId');
        });

        Schema::table(
            'employee_devices',
            function ($table) {
                //$table->dropForeign('deviceId');
        });

        Schema::table(
            'companies_devices',
            function ($table) {
                //$table->dropForeign('deviceId');
        });
        */

        $this->forceDropTable($this->tableName);
    }
}
