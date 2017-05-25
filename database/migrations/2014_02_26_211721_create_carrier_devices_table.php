<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateCarrierDevicesTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'carrier_devices';

    protected $foreignColumns = [
        'statusId' => 'nullable',
        'carrierId' => 'nullable',
        'deviceTypeId' => 'nullable',
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
                $table->string('make')->nullable();
                $table->string('model')->nullable();
                $table->string('makeModel')->nullable();
                $table->string('WA_alias')->nullable();
                $table->string('class')->nullable();
                $table->string('deviceOS')->nullable();
                $table->string('description')->nullable();
                $table->boolean('isLive')->default(0);
                $table->integer('externalId')->default(0);
                $table->integer('statusId')->unsigned()->nullable();
                $table->integer('carrierId')->unsigned()->nullable();
                $table->integer('deviceTypeId')->unsigned()->nullable();
            });

        Schema::table(
            $this->tableName,
            function ($table) {
                // ¿¿ $table->foreign('statusId')->references('id')->on('companies'); ??
                $table->foreign('carrierId')->references('id')->on('carriers');
                $table->foreign('deviceTypeId')->references('id')->on('device_types');
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
                //$table->dropForeign('deviceTypeId');
            });

        /*
        Schema::table(
            $this->tableName,
            function ( $table) {
                // //$table->dropForeign('statusId');
        });
        */

        $this->forceDropTable($this->tableName);
    }
}
