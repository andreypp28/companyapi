<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeletePackageConditionsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'package_conditions';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->forceDropTable($this->tableName);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
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
}