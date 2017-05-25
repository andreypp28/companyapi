<?php


use Illuminate\Database\Migrations\Migration;

class CreateApps extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'apps';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('type');
                $table->string('image');
                $table->string('description');

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

/* EXAMPLE:

{
  "id": 1,
  "type": "Comercial",
  "image": "http://webpage/image/image.png",
  "description": "this comercial app",
  "links": {
    "self": {
      "href": "/app/1"
    }
  }
}

*/
