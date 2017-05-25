<?php

use Illuminate\Database\Migrations\Migration;

class CreateDeviceVariationImagesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'deviceVariation_images';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('deviceVariationId')->unsigned();
                $table->integer('imageId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('deviceVariationId')->references('id')->on('device_variations')->onDelete('cascade');
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
