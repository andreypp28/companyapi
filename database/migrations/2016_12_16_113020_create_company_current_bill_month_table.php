<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyCurrentBillMonthTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'company_current_bill_months';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('carrierId')->unsigned();
                $table->integer('companyId')->unsigned();
                $table->date('currentBillMonth')->nullable();
                $table->nullableTimestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->forceDropTable($this->tableName);
    }
}
