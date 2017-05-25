<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteCompaniesDevicesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'companies_devices';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('companies_devices_companyid_foreign');
                $table->dropColumn('companyId');
        });
        
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('companies_devices_deviceid_foreign');
                $table->dropColumn('deviceId');
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
                $table->integer('companyId')->unsigned();
                $table->integer('deviceId')->unsigned();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('companyId')->references('id')->on('companies')->onDelete('cascade');
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
            }
        );
    }
}