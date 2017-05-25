<?php

//use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConditionOperatorsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'condition_operators';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('originalName');
                $table->string('apiName');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->forceDropTable($this->tableName);
    }
}
