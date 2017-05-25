
<?php


use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    use \WA\Database\Command\TablesRelationsAndIndexes;

    protected $tableName = 'notification';

    protected $foreignColumns = [
        'category_id',
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
                $table->bigInteger('from_id')->index()->unsigned();
                $table->string('from_type')->index()->nullable();
                $table->bigInteger('to_id')->index()->unsigned();
                $table->string('to_type')->index()->nullable();
                $table->string('url');
                $table->string('extra')->nullable();
                $table->tinyInteger('read')->default(0);
                $table->timestamp('expire_time')->nullable();
                $table->integer('category_id')->unsigned()->nullable();

                $table->timestamps();
            });

        Schema::table(
            $this->tableName,
            function ($table) {
                //$table->foreign('category_id')->references('id')->on('companies');
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
                // //$table->dropForeign('category_id');
            });

        $this->forceDropTable($this->tableName);
    }
}
