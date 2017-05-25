<?php

use Illuminate\Database\Migrations\Migration;

class CreatePackages extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'packages';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('name');
                $table->integer('addressId')->unsigned();
                $table->integer('companyId')->unsigned();
                
                $table->nullableTimestamps();
            }
        );

        Schema::table(
            'orders',
            function ($table) {
                $table->foreign('packageId')->references('id')->on('packages');
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
                //$table->dropForeign('devicesId');
                //$table->dropForeign('appsId');
            }
        );

        Schema::table(
            'orders', 
            function ($table) {
                //$table->dropForeign('packageId');
            }
        );

        $this->forceDropTable($this->tableName);
    }
}

/* EXAMPLE

{
    "id": 1,
    "name": "",
    "links": {
        "self": {
          "href": "/package/1"
        },
        "condition": {
          "href": "/package/1?_include=condition"
        },
        "devices": {
          "href": "/package/1?_include=devices"
        },
        "apps": {
          "href": "/package/1?_include=apps"
        },
        "service": {
          "href": "/package/1?_include=services"
        }
    }
}
*/
