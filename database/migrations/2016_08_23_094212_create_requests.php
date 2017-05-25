<?php


use Illuminate\Database\Migrations\Migration;

class CreateRequests extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'requests';
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
                $table->string('description');
                $table->string('type');

                $table->nullableTimestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $this->forceDropTable($this->tableName);
    }
}

/* EXAMPLE

{
  "id": 1,
  "name": "new",
  "description": "",
  "links": {
    "self": {
      "href": "/request/{type}/1"
    }
  }
}
*/
