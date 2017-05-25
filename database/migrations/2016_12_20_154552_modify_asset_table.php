<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAssetTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'assets';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->tableName,
            function ($table) {
                $table->integer('userId')->unsigned()->nullable();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('assets_userid_foreign');
                $table->dropColumn('userId');
        });
    }
}
