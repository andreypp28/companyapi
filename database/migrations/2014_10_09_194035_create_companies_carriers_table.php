<?php


use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateCompaniesCarriersTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'companies_carriers';

    protected $foreignColumns = [
        'carrierId',
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
                $table->string('billingAccountNumber');
                $table->string('parentAccountNumber')->nullable();
                $table->string('accountName')->nullable();
                $table->decimal('carrierDiscount', 10, 2)->nullable();
                $table->boolean('active')->default(0);
                $table->integer('carrierId')->unsigned();
                $table->integer('companyId')->unsigned();

                $table->nullableTimestamps();
            });

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('carrierId')->references('id')->on('carriers');
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
                //$table->dropForeign('carrierId');
                //$table->dropForeign('companyId');
            });

        $this->forceDropTable($this->tableName);
    }
}
