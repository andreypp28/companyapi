<?php

use Illuminate\Database\Migrations\Migration;

class CreateCompaniesCurrentBillMonth extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('companies', function ($table) {
            $table->date('currentBillMonth')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('companies', function ($table) {
            $table->dropColumn('currentBillMonth');
        });
    }
}
