<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPresetTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'presets';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table(
            $this->tableName,
            function ($table) {
                $table->integer('companyId')->unsigned()->nullable();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('companyId')->references('id')->on('companies')->onDelete('cascade');
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
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('presets_companyid_foreign');
                $table->dropColumn('companyId');
        });
    }
}
