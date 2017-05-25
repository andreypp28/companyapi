<?php

use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'address';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('country')->nullable();
                $table->string('postalCode')->nullable();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            'packages',
            function ($table) {
                $table->foreign('addressId')->references('id')->on('address')->onDelete('cascade');
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
