<?php


use Illuminate\Database\Migrations\Migration;

class CreateUdlPathsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('udl_value_paths', function ($table) {
            $table->increments('id');
            $table->string('udlPath');
            $table->integer('udlId');
            $table->integer('externalId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('udl_value_paths');
    }
}
