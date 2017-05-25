<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteCompaniesCarriersTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'companies_carriers';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('billingAccountNumber');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('parentAccountNumber');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('accountName');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('carrierDiscount');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropColumn('active');
        });

        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('companies_carriers_companyid_foreign');
                $table->dropColumn('companyId');
        });
        
        Schema::table(
            $this->tableName, function ($table) {
                $table->dropForeign('companies_carriers_carrierid_foreign');
                $table->dropColumn('carrierId');
        });

        $this->forceDropTable($this->tableName);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
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
                $table->integer('companyId')->unsigned();
                $table->integer('carrierId')->unsigned();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('companyId')->references('id')->on('companies')->onDelete('cascade');
                $table->foreign('carrierId')->references('id')->on('carriers')->onDelete('cascade');
            }
        );
    }
}
