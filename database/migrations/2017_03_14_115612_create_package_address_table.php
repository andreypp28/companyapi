<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageAddressTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'package_address';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('packageId')->unsigned();
                $table->integer('addressId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('packageId')->references('id')->on('packages')->onDelete('cascade');
                $table->foreign('addressId')->references('id')->on('address')->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->forceDropTable($this->tableName);
    }
}
