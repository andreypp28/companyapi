<?php

use Illuminate\Database\Migrations\Migration;

class CreateUdlValuesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            'udl_values',
            function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->integer('udlId', false, true)->nullable();
                $table->integer('externalId')->nullable();

//                $table->unique(['name','udlId']);
                $table->nullableTimestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('udl_values');
    }
}
