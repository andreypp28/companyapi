<?php

use Illuminate\Database\Migrations\Migration;

class CreateCustomRequests extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'custom_requests';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->integer('userId')->unsigned();
                $table->string('subject');
                $table->string('description');
                $table->string('reference');
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                $table->foreign('userId')->references('id')->on('users');
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
            });

        $this->forceDropTable($this->tableName);
    }
}

/* EXAMPLE

{
  "id": 1,
  "idEmployee": "WA-C4KX6JKVHA",
  "subject": "Meizu Note",
  "description": "I need this device for testing purposes",
  "reference": "http://www.amazon.com",
  "links": {
    "self": {
      "href": "customrequest/1"
    }
  }
}

*/
