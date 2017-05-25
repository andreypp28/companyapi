<?php

use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'contents';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->tableName, function ($table) {
            $table->increments('id');
            $table->text('content');
            $table->boolean('active')->default(0);
            $table->integer('owner_id');
            $table->string('owner_type');
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
