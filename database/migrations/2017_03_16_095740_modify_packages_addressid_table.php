<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPackagesAddressidTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'packages';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('packages_addressid_foreign');
                $table->dropColumn('addressId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
                Schema::table(
            $this->tableName, function ($table) {
                $table->integer('addressId')->unsigned()->nullable();
                $table->foreign('addressId')->references('id')->on('address')->onDelete('cascade');
            }
        );
    }
}
