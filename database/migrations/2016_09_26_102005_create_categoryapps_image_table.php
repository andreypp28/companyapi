<?php

use Illuminate\Database\Migrations\Migration;

class CreateCategoryappsImageTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'categoryapps_image';

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
                $table->integer('imageId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('categoryappId')->references('id')->on('categoryapps')->onDelete('cascade');
                $table->foreign('imageId')->references('id')->on('images')->onDelete('cascade');
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
