<?php


use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateDeviceTypesTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'device_types';

    protected $foreignColumns = [
        'statusId' => 'nullable',
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
                $table->string('make');
                $table->string('model');
                $table->string('class');
                $table->string('deviceOS')->nullable();
                $table->string('description')->nullable();
                $table->integer('statusId')->unsigned()->nullable();
                $table->string('image')->nullable();
            });

        Schema::table(
            $this->tableName,
            function ($table) {
                // ¿¿ $table->foreign('statusId')->references('id')->on('companies'); ??
            }
        );

        Schema::table(
            'devices',
            function ($table) {
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
            'devices',
            function ($table) {
                //$table->dropForeign('deviceTypeId');
            });

        /*
        Schema::table(
            $this->tableName,
            function ( $table) {
                // //$table->dropForeign('statusId');
        });

        Schema::table(
            'carrier_devices',
            function ($table) {
                //$table->dropForeign('deviceTypeId');
        });
        */

        $this->forceDropTable($this->tableName);
    }
}
