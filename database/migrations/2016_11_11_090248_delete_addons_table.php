<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteAddonsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'addons';

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
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->integer('cost');
                $table->integer('serviceId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('serviceId')->references('id')->on('services')->onDelete('cascade');
            }
        );
    }
}
