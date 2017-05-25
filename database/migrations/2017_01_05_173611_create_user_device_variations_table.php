<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDeviceVariationsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'user_device_variations';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists($this->tableName);

        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('userId')->unsigned();
                $table->integer('deviceVariationId')->unsigned();                

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('deviceVariationId')->references('id')->on('device_variations')->onDelete('cascade');
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
                $table->dropForeign('user_device_variations_userid_foreign');
                $table->dropColumn('userId');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('user_device_variations_devicevariationid_foreign');
                $table->dropColumn('deviceVariationId');
        });

        $this->forceDropTable($this->tableName);
    }
}
