<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceVariationsModificationsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'device_variations_modifications';

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
                $table->integer('deviceVariationId')->unsigned();
                $table->integer('modificationId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('deviceVariationId')->references('id')->on('device_variations')->onDelete('cascade');
                $table->foreign('modificationId')->references('id')->on('modifications')->onDelete('cascade');
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
                $table->dropForeign('device_variations_modifications_devicevariationid_foreign');
                $table->dropColumn('deviceVariationId');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('device_variations_modifications_modificationid_foreign');
                $table->dropColumn('modificationId');
        });

        $this->forceDropTable($this->tableName);
    }
}
