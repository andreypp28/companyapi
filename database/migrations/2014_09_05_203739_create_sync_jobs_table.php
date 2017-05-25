<?php


use Illuminate\Database\Migrations\Migration;

class CreateSyncJobsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'sync_jobs';

    protected $foreignColumns = [
        'statusId' => 'job_statuses',
    ];

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sync_jobs', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('statusId');
            $table->text('notes')->nullable();
            $table->timestamps();
        }
        );

        Schema::table(
            'devices',
            function ($table) {
                $table->foreign('syncId')->references('id')->on('sync_jobs');
            }
        );

        Schema::table(
            'assets',
            function ($table) {
                $table->foreign('syncId')->references('id')->on('sync_jobs');
            }
        );

        Schema::table(
            'users',
            function ($table) {
                $table->foreign('syncId')->references('id')->on('sync_jobs');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table(
            'devices',
            function ($table) {
                //$table->dropForeign('syncId');
            });

        Schema::table(
            'assets',
            function ($table) {
                //$table->dropForeign('syncId');
            });

        Schema::table(
            'users',
            function ($table) {
                //$table->dropForeign('syncId');
            });

        $this->forceDropTable($this->tableName);
    }
}
