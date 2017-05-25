<?php

use Illuminate\Database\Migrations\Migration;

class CreateServices extends Migration {
	use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'services';
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('title');
                $table->integer('planCode');
                $table->integer('cost');
                $table->string('description');
                $table->integer('domesticMinutes');
                $table->integer('domesticData');
                $table->integer('domesticMessages');
                $table->integer('internationalMinutes');
                $table->integer('internationalData');
                $table->integer('internationalMessages');

				$table->nullableTimestamps();

				//$table->integer('companyId')->unsigned();
			}
		);

        Schema::table(
            $this->tableName,
            function ($table) {
                //$table->foreign('companyId')->references('id')->on('companies');
            }
        );
        Schema::table(
            'orders',
            function ($table) {
                $table->foreign('serviceId')->references('id')->on('services');
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
                //$table->dropForeign('companyId');
            });

		$this->forceDropTable($this->tableName);
	}
}