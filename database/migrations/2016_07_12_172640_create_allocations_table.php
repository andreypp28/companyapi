<?php


use Illuminate\Database\Migrations\Migration;

class CreateAllocationsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'allocations';

    protected $foreignColumns = [
        'userId',
        'companyId',
    ];

    /**
     * Run the migrations.asset_devices.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('userId')->unsigned();
                $table->integer('companyId')->unsigned();

                $table->date('billMonth');
                $table->string('mobileNumber');
                $table->string('carrier');
                $table->string('currency');
                $table->string('handsetModel');

                $table->decimal('totalAllocatedCharge');
                $table->decimal('preAllocatedAmountDue');
                $table->decimal('otherAdjustments');
                $table->decimal('preAdjustedAccessCharge');

                // Service Plan Charges
                $table->decimal('adjustedAccessCharge');
                $table->decimal('bBCharge');
                $table->decimal('pDACharge');
                $table->decimal('iPhoneCharge');
                $table->decimal('featuresCharge');
                $table->decimal('dataCardCharge');
                $table->decimal('lDCanadaCharge');
                $table->decimal('uSAddOnPlanCharge');
                $table->decimal('uSLDAddOnPlanCharge');
                $table->decimal('uSDataRoamingCharge');
                $table->decimal('nightAndWeekendAddOnCharge');
                $table->decimal('minuteAddOnCharge');

                $table->decimal('servicePlanCharge');

                //Usage Charges
                $table->decimal('directConnectCharge');
                $table->decimal('textMessagingCharge');
                $table->decimal('dataCharge');
                $table->decimal('intlRoamingCharge');
                $table->decimal('intlLongDistanceCharge');
                $table->decimal('directoryAssistanceCharge');
                $table->decimal('callForwardingCharge');
                $table->decimal('airtimeCharge');

                // summation of usage charges
                $table->decimal('usageCharge');

                //Other Charges
                $table->decimal('equipmentCharge');
                $table->decimal('otherDiscountCharge');
                $table->decimal('taxesCharge');
                $table->decimal('thirdPartyCharge');

                //summation of other charges
                $table->decimal('otherCharge');

                //Fees
                $table->decimal('waFees');
                $table->decimal('lineFees');
                $table->decimal('mobilityFees');

                //summation of fees
                $table->decimal('feesCharge');

                //last upgrade date
                $table->string('last_upgrade');

                //Other charges and usage data
                $table->string('deviceType');
                $table->decimal('domesticUsageCharge');
                $table->integer('domesticDataUsage');
                $table->integer('domesticVoiceUsage');
                $table->integer('domesticTextUsage');
                $table->decimal('intlRoamUsageCharge');
                $table->integer('intlRoamDataUsage');
                $table->integer('intlRoamVoiceUsage');
                $table->integer('intlRoamTextUsage');
                $table->decimal('intlLDUsageCharge');
                $table->integer('intlLDVoiceUsage');
                $table->integer('intlLDTextUsage');
                $table->decimal('etfCharge');
                $table->decimal('otherCarrierCharge');
                $table->string('deviceEsnImei');
                $table->string('deviceSim');


            });

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('userId')->references('id')->on('users');
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
                //$table->dropForeign('userId');
                //$table->dropForeign('companyId');
            });

        $this->forceDropTable($this->tableName);
    }
}
