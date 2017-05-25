<?php

use Illuminate\Database\Migrations\Migration;

class CreateCompaniesShortName extends Migration
{
    /**
   * Run the migrations.
   */
  public function up()
  {
      Schema::table('companies', function ($table) {
          $table->string('shortName')->before('rawDataDirectoryPath')->default('shortName');
      });
  }

  /**
   * Reverse the migrations.
   */
  public function down()
  {
      Schema::table('companies', function ($table) {
          $table->dropColumn('shortName');
      });
  }
}
