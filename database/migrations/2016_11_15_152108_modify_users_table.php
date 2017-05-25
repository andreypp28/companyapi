<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class ModifyUsersTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'users';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->integer('addressId')->unsigned()->nullable();
                $table->integer('departmentId')->unsigned()->nullable();
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->foreign('addressId')->references('id')->on('address');
                $table->foreign('departmentId')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('users_addressid_foreign');
                $table->dropColumn('addressId');
        });
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('users_departmentid_foreign');
                $table->dropColumn('departmentId');
        });
    }
}
