<?php

use Illuminate\Database\Migrations\Migration;

class CreateCarrierImagesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'carrier_images';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('carrierId')->unsigned();
                $table->integer('imageId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('carrierId')->references('id')->on('carriers')->onDelete('cascade');
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
