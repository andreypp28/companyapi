<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyConditionsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'conditions';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn($this->tableName, 'typeCond')) {
            Schema::table(
                $this->tableName, function ($table) {
                    $table->dropColumn('typeCond');
            });    
        }

        Schema::table(
            $this->tableName, function ($table) {
                $table->integer('packageId')->unsigned()->nullable();
                $table->foreign('packageId')->references('id')->on('packages')->onDelete('cascade');
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
                $table->string('typeCond')->nullable();
        });
    }
}
