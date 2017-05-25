<?php

use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            'departments',
            function ($table) {
                $table->increments('id');

                $table->integer('companyId', false, true);
                $table->integer('managerId', false, true);
                $table->string('name');
                $table->string('label');
                $table->string('description')->nullable();
                $table->string('departmentCode')->nullable();
                $table->text('comments')->nullable();

                $table->nullableTimestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('departments');
    }
}
