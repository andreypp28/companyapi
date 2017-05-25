<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateUserAssetsTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'user_assets';

    protected $foreignColumns = [
        'userId',
        'assetId',
    ];

    /**
     * Run the migrations.
     */
    public function up()
    {
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
                $table->foreign('assetId')->references('id')->on('assets');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table(
            $this->tableName,
            function ($table) {
                //$table->dropForeign('assetId');
            });

        /*
        Schema::table(
            $this->tableName,
            function ( $table) {
                //$table->dropForeign('userId');
        });
        */

        $this->forceDropTable($this->tableName);
    }
}
