<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateCarriersTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'carriers';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->string('presentation');
                $table->boolean('active')->default(0);
                $table->integer('locationId')->unsigned();
                $table->string('shortName');

                $table->nullableTimestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        /*
        Schema::table(
            'devices',
            function ($table) {
                //$table->dropForeign('carrierId');
        });

        Schema::table(
            'assets',
            function ($table) {
                //$table->dropForeign('carrierId');
        });

        Schema::table(
            'carrier_devices',
            function ($table) {
                //$table->dropForeign('carrierId');
        });

        Schema::table(
            'companies_carriers',
            function ($table) {
                //$table->dropForeign('carrierId');
        });
        */

        $this->forceDropTable($this->tableName);
    }
}
