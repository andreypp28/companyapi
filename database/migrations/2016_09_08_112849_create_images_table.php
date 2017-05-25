<?php

use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'images';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('originalName')->nullable();
                $table->string('filename')->nullable();
                $table->string('mimeType')->nullable();
                $table->string('extension')->nullable();
                $table->integer('size')->nullable();
                $table->string('url')->nullable();

                $table->nullableTimestamps();
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
