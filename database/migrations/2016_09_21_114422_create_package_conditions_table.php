<?php

use Illuminate\Database\Migrations\Migration;

class CreatePackageConditionsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'package_conditions';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists($this->tableName);

        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('packageId')->unsigned();
                $table->integer('conditionId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('packageId')->references('id')->on('packages')->onDelete('cascade');
                $table->foreign('conditionId')->references('id')->on('conditions')->onDelete('cascade');
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
