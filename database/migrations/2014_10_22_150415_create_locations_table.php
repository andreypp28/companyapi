<?php


use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'locations';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->tableName, function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('fullName');
            $table->string('iso2', 5);
            $table->string('iso3', 5);
            $table->string('region')->nullable();
            $table->string('currency')->nullable();
            $table->string('numCode')->nullable();
            $table->string('callingCode')->nullable();
            $table->string('lang')->nullable();
            $table->string('currencyIso', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop($this->tableName);
    }
}
