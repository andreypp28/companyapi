<?php

/**
 * company_saml2 - Table of the Companies with saml2_settings configuration.
 *
 * @author   Agustí Dosaiguas
 */
use Illuminate\Database\Migrations\Migration;

class CreateCompanySaml2 extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'company_saml2';

    protected $foreignColumns = [
        'companyId',
    ];

    /**
     * Run the migrations.
     */
    public function up()
    {
        // config/saml2_settings.
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('entityId');
                $table->string('singleSignOnServiceUrl');
                $table->string('singleSignOnServiceBinding');
                $table->string('singleLogoutServiceUrl');
                $table->string('singleLogoutServiceBinding');
                $table->longtext('x509cert');
                $table->integer('companyId')->unsigned();

                $table->nullableTimestamps();
            }
        );
        Schema::table(
            $this->tableName,
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
            $this->tableName,
            function ($table) {
                //$table->dropForeign('companyId');
            });

        $this->forceDropTable($this->tableName);
    }
}
