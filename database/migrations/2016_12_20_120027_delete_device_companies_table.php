<?php

use Illuminate\Database\Migrations\Migration;

class DeleteDeviceCompaniesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'device_companies';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('device_companies_deviceid_foreign');
                $table->dropColumn('deviceId');
        });
        
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('device_companies_companyid_foreign');
                $table->dropColumn('companyId');
        });

        $this->forceDropTable($this->tableName);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('deviceId')->unsigned();
                $table->integer('companyId')->unsigned();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
                $table->foreign('companyId')->references('id')->on('companies')->onDelete('cascade');
            }
        );
    }
}