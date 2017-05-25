<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateAttributablesTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'attributables';

    protected $foreignColumns = [
        'dataOriginationId' => 'nullable',
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
                $table->string('value')->nullable();
                $table->string('attributable_type');
                $table->string('attribute_id');
                $table->integer('attributable_id');
                $table->index(['attribute_id', 'attributable_id']);
                $table->integer('dataOriginationId')->unsigned()->nullable();
            });

        Schema::table(
            $this->tableName,
            function ($table) {
                // ¿¿ $table->foreign('dataOriginationId')->references('id')->on('companies'); ??
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
                ////$table->dropForeign('dataOriginationId');
            });

        $this->forceDropTable($this->tableName);
    }
}
