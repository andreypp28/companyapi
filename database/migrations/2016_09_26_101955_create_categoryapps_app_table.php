<?php

use Illuminate\Database\Migrations\Migration;

class CreateCategoryappsAppTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'categoryapps_app';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('categoryappId')->unsigned();
                $table->integer('appId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('categoryappId')->references('id')->on('categoryapps')->onDelete('cascade');
                $table->foreign('appId')->references('id')->on('apps')->onDelete('cascade');
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
