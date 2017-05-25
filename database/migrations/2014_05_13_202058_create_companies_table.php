<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateCompaniesTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'companies';

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
                $table->string('label');
                $table->boolean('active')->default(0);
                $table->string('udlpath')->nullable();
                $table->boolean('isCensus')->nullable();
                $table->string('udlPathRule')->nullable();
                $table->string('assetPath');
                $table->nullableTimestamps();
            }
        );

        Schema::table(
            'users',
            function ($table) {
                $table->foreign('companyId')->references('id')->on('companies');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table(
            'users',
            function ($table) {
                //$table->dropForeign('companyId');
            });

        /*
        Schema::table(
            'companies_devices',
            function ($table) {
                //$table->dropForeign('companyId');
        });

        Schema::table(
            'companies_carriers',
            function ($table) {
                //$table->dropForeign('companyId');
        });

        Schema::table(
            'company_domains',
            function ( $table) {
            //$table->dropForeign('companyId');
        });

        Schema::table(
            'allocations',
            function ( $table) {
            //$table->dropForeign('companyId');
        });

        Schema::table(
            'company_saml2',
            function ( $table) {
            //$table->dropForeign('companyId');
        });
        */

        $this->forceDropTable($this->tableName);
    }
}
