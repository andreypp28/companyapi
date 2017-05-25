<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyServicesTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'services';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('domesticMinutes');
        });
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('domesticData');
        });
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('domesticMessages');
        });
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('internationalMinutes');
        });
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('internationalData');
        });
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('internationalMessages');
        });        
        Schema::table(
            $this->tableName, function ($table) {
                $table->integer('carrierId')->unsigned()->nullable();
                $table->string('status')->nullable();
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->foreign('carrierId')->references('id')->on('carriers');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->integer('domesticMinutes')->default(0);
                $table->integer('domesticData')->default(0);
                $table->integer('domesticMessages')->default(0);
                $table->integer('internationalMinutes')->default(0);
                $table->integer('internationalData')->default(0);
                $table->integer('internationalMessages')->default(0);
        });
        
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('services_carrierid_foreign');
                $table->dropColumn('carrierId');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('status');
        });
    }
}
