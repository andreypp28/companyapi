<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class ModifyAddressTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'address';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->string('name')->nullable();
                $table->string('attn')->nullable();
                $table->string('phone')->nullable();
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
                $table->dropColumn('name');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('attn');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('phone');
        });
    }
}
