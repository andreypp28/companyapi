<?php

use Illuminate\Database\Migrations\Migration;

class CreateConditionsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'conditions';

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            $this->tableName,
            function ($table) {
                $table->increments('id');
                $table->string('typeCond');
                $table->string('name');
                $table->string('condition');
                $table->string('value');

                $table->nullableTimestamps();
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
                //$table->dropForeign('conditionsId');
            });

        $this->forceDropTable($this->tableName);
    }
}

/* EXAMPLE

{
  "data": [
    {
      "type": "conditions",
      "id": "1",
      "attributes": {
        "typeCond": "Employee Profile",
        "name": "Name",
        "condition": "",
        "value": "",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/1"
      }
    },
    {
      "type": "conditions",
      "id": "2",
      "attributes": {
        "typeCond": "Employee Profile",
        "name": "Email",
        "condition": "",
        "value": "",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/2"
      }
    },
    {
      "type": "conditions",
      "id": "3",
      "attributes": {
        "typeCond": "Employee Profile",
        "name": "Position",
        "condition": "like",
        "value": "Engineer",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/3"
      }
    },
    {
      "type": "conditions",
      "id": "4",
      "attributes": {
        "typeCond": "Employee Profile",
        "name": "Level",
        "condition": "gt",
        "value": "3",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/4"
      }
    },
    {
      "type": "conditions",
      "id": "5",
      "attributes": {
        "typeCond": "Employee Profile",
        "name": "Division",
        "condition": "like",
        "value": "Sales",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/5"
      }
    },
    {
      "type": "conditions",
      "id": "6",
      "attributes": {
        "typeCond": "Employee Profile",
        "name": "Cost Center",
        "condition": "",
        "value": "",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/6"
      }
    },
    {
      "type": "conditions",
      "id": "7",
      "attributes": {
        "typeCond": "Employee Profile",
        "name": "Budget",
        "condition": "lt",
        "value": "600",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/7"
      }
    },
    {
      "type": "conditions",
      "id": "8",
      "attributes": {
        "typeCond": "Location",
        "name": "Country",
        "condition": "contains",
        "value": "USA",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/8"
      }
    },
    {
      "type": "conditions",
      "id": "9",
      "attributes": {
        "typeCond": "Employee Profile",
        "name": "Country",
        "condition": "contains",
        "value": "Canada",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/9"
      }
    },
    {
      "type": "conditions",
      "id": "10",
      "attributes": {
        "typeCond": "Employee Profile",
        "name": "City",
        "condition": "",
        "value": "",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/10"
      }
    },
    {
      "type": "conditions",
      "id": "11",
      "attributes": {
        "typeCond": "Employee Profile",
        "name": "Address",
        "condition": "",
        "value": "",
        "created_at": null,
        "updated_at": null
      },
      "links": {
        "self": "clean.api/conditions/11"
      }
    }
  ],
  "meta": {
    "sort": "",
    "filter": [],
    "fields": [],
    "pagination": {
      "total": 11,
      "count": 11,
      "per_page": 25,
      "current_page": 1,
      "total_pages": 1
    }
  },
  "links": {
    "self": "http://clean.api/conditions?page=1",
    "first": "http://clean.api/conditions?page=1",
    "last": "http://clean.api/conditions?page=1"
  }
}
*/
