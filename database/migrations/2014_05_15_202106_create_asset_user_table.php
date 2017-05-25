<?php

use Illuminate\Database\Migrations\Migration;

class CreateAssetUserTable extends Migration
{
    protected $tableName = 'employee_assets';
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('assetId', false, true)->index();
                $table->integer('userId', false, true)->index();
                $table->nullableTimestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop($this->tableName);
    }
}
