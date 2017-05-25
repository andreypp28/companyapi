<?php


use Illuminate\Database\Migrations\Migration;

class CreateCompanySystemRulesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('company_rules', function ($table) {
            $table->integer('companyId')->unsigned();
            $table->integer('ruleId')->unsigned();
            $table->integer('priority')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('company_rules');
    }
}
