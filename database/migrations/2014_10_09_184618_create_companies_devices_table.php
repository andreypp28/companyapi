<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateCompaniesDevicesTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'companies_devices';

    protected $foreignColumns = [
        'companyId',
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
                $table->integer('companyId')->unsigned();
                $table->integer('deviceId')->unsigned();
            });

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('companyId')->references('id')->on('companies');
                $table->foreign('deviceId')->references('id')->on('devices');
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
                //$table->dropForeign('companyId');
                //$table->dropForeign('deviceId');
            });

        $this->forceDropTable($this->tableName);
    }
}
