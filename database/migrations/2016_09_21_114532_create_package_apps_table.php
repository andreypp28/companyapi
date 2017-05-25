<?php

use Illuminate\Database\Migrations\Migration;

class CreatePackageAppsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'package_apps';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('packageId')->unsigned();
                $table->integer('appId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('packageId')->references('id')->on('packages')->onDelete('cascade');
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
