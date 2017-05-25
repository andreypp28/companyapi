<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserUdlsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            'user_udls',
            function ($table) {
                $table->increments('id');

                $table->integer('userId', false, true);
                $table->integer('udlValueId', false, true);
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('user_udls');
    }
}
