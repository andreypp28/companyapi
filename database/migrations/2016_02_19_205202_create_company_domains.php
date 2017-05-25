<?php


use Illuminate\Database\Migrations\Migration;

class CreateCompanyDomains extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'company_domains';

    protected $foreignColumns = [
        'companyId',
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
                $table->string('domain');
                $table->boolean('active')->default(true);
                $table->integer('companyId')->unsigned();

                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('companyId')->references('id')->on('companies');
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
