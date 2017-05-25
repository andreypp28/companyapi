<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCompaniesTable extends Migration
{   
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'companies';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->string('defaultLocation')->nullable();
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('defaultLocation');
        });
    }
}
