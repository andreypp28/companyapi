<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresetDeviceVariations extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $from = 'preset_devices';
    protected $to = 'preset_device_variations';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->from, function ($table) {
                $table->dropForeign('preset_devices_deviceid_foreign');
                $table->dropColumn('deviceId');
        });     

        Schema::rename($this->from, $this->to);

        Schema::table(
            $this->to,
            function ($table) {
                $table->integer('deviceVariationId')->unsigned()->nullable();
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
            $this->to, function ($table) {
                $table->dropForeign('preset_device_variations_devicevariationid_foreign');
                $table->dropColumn('deviceVariationId');
        });

        Schema::rename($this->to, $this->from);

        Schema::table(
            $this->from,
            function ($table) {
                $table->integer('deviceId')->unsigned()->nullable();
                $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
            }
        );
    }
}
