<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateAssetsTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'assets';

    protected $foreignColumns = [
        'typeId' => 'nullable',
        'carrierId' => 'nullable',
        'statusId' => 'nullable',
        'syncId' => 'nullable',
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
                $table->string('identification');
                $table->boolean('active')->default(1);
                $table->integer('externalId')->unique()->nullable();
                $table->integer('typeId')->unsigned()->nullable();
                $table->integer('carrierId')->unsigned()->nullable();
                $table->integer('statusId')->unsigned()->nullable();
                $table->integer('syncId')->unsigned()->nullable();

                $table->softDeletes();
                $table->timestamps();
            });

        Schema::table(
            $this->tableName,
            function ($table) {
                //¿¿ $table->foreign('typeId')->references('id')->on('companies'); ??
                $table->foreign('carrierId')->references('id')->on('carriers');
                //¿¿ $table->foreign('statusId')->references('id')->on('companies'); ??
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
                //$table->dropForeign('carrierId');
            });

        /*
        Schema::table(
            $this->tableName,
            function ( $table) {
                //$table->dropForeign('syncId');
                ////$table->dropForeign('typeId');
                ////$table->dropForeign('statusId');
        });

        Schema::table(
            'user_assets',
            function ( $table) {
                //$table->dropForeign('assetId');
        });

        Schema::table(
            'asset_devices',
            function ( $table) {
                //$table->dropForeign('assetId');
        });
        */

        $this->forceDropTable($this->tableName);
    }
}
