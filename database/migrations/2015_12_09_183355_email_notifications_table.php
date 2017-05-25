<?php


use Illuminate\Database\Migrations\Migration;

class EmailNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('email_notifications', function ($table) {
            $table->increments('id');
            $table->bigInteger('user_id')->index()->unsigned();
            $table->integer('category_id')->index()->unsigned();
            $table->mediumText('data')->nullable();
            $table->tinyInteger('read')->default(0);
            $table->timestamp('sent_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('email_notifications');
    }
}
