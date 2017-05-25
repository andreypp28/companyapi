<?php

use Illuminate\Database\Migrations\Migration;

class CreateNotificationGroupsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notification_groups', function ($table) {
            $table->increments('id');
            $table->string('name', 50)->index()->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('notification_groups');
    }
}
