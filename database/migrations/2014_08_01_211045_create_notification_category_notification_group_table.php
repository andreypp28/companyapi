<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateNotificationCategoryNotificationGroupTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'notifications_categories_in_groups';

    protected $foreignColumns = [
        'category_id',
        'group_id',
    ];

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create($this->tableName, function ($table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('group_id')->unsigned()->nullable();
        });

        Schema::table(
            $this->tableName,
            function ($table) {
                // ¿¿ $table->foreign('category_id')->references('id')->on('companies'); ??
                // ¿¿ $table->foreign('group_id')->references('id')->on('companies'); ??
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
                ////$table->dropForeign('category_id');
            ////$table->dropForeign('group_id');
            });
        $this->forceDropTable($this->tableName);
    }
}
