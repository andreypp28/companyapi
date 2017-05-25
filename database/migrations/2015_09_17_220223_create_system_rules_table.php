<?php


use Illuminate\Database\Migrations\Migration;

class CreateSystemRulesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('system_rules', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->string('description');
            $table->string('processorName');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('system_rules');
    }
}
