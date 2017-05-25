<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Add dumpId and carrierId to the allocations table.
 *
 * dumpId must not have an FK definition - this is the dumpId from clean-prime, used for tracking
 * the status of live allocations from the CLEAN Prime interface.
 *
 * Class AddDumpIdToAllocationsTable
 */
class AddDumpIdToAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('allocations', function ($table) {
            $table->integer('dumpId')->unsigned()->default(0);
            $table->integer('carrierId')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('allocations', function ($table) {
            $table->dropColumn('dumpId', 'carrierId');
        });
    }
}
