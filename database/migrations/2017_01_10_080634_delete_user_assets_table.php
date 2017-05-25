<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteUserAssetsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'user_assets';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('user_assets_userid_foreign');
                $table->dropColumn('userId');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('user_assets_assetid_foreign');
                $table->dropColumn('assetId');
        });

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
                $table->integer('userId')->unsigned();
                $table->integer('assetId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('assetId')->references('id')->on('assets')->onDelete('cascade');
            }
        );
    }
}
