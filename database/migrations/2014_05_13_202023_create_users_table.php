<?php

use Illuminate\Database\Migrations\Migration;
use WA\Database\Command\TablesRelationsAndIndexes;

class CreateUsersTable extends Migration
{
    use TablesRelationsAndIndexes;

    protected $tableName = 'users';
    protected $tableNamePassword = 'password_reminders';

    protected $foreignColumns = [
        'companyId' => 'nullable',
        'syncId' => 'nullable',
        'supervisorId' => 'nullable',
        'externalId' => 'nullable',
        'approverId' => 'nullable',
        'defaultLocationId' => 'nullable',
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
                $table->string('uuid');
                $table->string('identification')->unique();
                $table->string('email')->nullable();
                $table->string('alternateEmail')->nullable();
                $table->string('password');
                $table->string('username');
                $table->string('confirmation_code')->nullable();
                $table->string('remember_token')->nullable();
                $table->boolean('confirmed')->default(false);
                $table->string('firstName')->nullable();
                $table->string('lastName')->nullable();
                $table->string('alternateFirstName')->nullable();
                $table->string('supervisorEmail')->nullable();
                $table->string('companyUserIdentifier')->nullable();
                $table->boolean('isSupervisor')->default(0);
                $table->boolean('isValidator')->default(0);
                $table->boolean('isActive')->default(1);
                $table->integer('rgt')->nullable();
                $table->integer('lft')->nullable();
                $table->integer('hierarchy')->nullable();
                $table->string('defaultLang');
                $table->text('notes')->nullable();
                $table->integer('level')->default(0);
                $table->boolean('notify');
                $table->integer('companyId')->unsigned()->nullable();
                $table->integer('syncId')->unsigned()->nullable();
                $table->integer('supervisorId')->unsigned()->nullable();
                $table->integer('externalId')->unsigned()->nullable();
                $table->integer('approverId')->unsigned()->nullable();
                $table->integer('defaultLocationId')->unsigned()->nullable();

                $table->softDeletes();
                $table->nullableTimestamps();
            }
        );

        Schema::table(
            $this->tableName,
            function ($table) {
                // ¿¿ $table->foreign('supervisorId')->references('id')->on('companies'); ??
                // ¿¿ $table->foreign('externalId')->references('id')->on('companies'); ??
                // ¿¿ $table->foreign('approverId')->references('id')->on('companies'); ??
                // ¿¿ $table->foreign('defaultLocationId')->references('id')->on('companies'); ??
            }
        );

        // Creates password reminders table
        Schema::create(
            $this->tableNamePassword,
            function ($table) {
                $table->string('email');
                $table->string('token');
                $table->timestamp('created_at');
            });

        Schema::table(
            'user_assets',
            function ($table) {
                $table->foreign('userId')->references('id')->on('users');
            }
        );

        Schema::table(
            'device_users',
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
            'user_assets',
            function ($table) {
                //$table->dropForeign('userId');
            });

        Schema::table(
            'device_users',
            function ($table) {
                //$table->dropForeign('userId');
            });

        /*
        Schema::table(
            $this->tableName,
            function ($table) {
            //$table->dropForeign('companyId');
            //$table->dropForeign('syncId');
            // //$table->dropForeign('supervisorId');
            // //$table->dropForeign('externalId');
            // //$table->dropForeign('approverId');
            // //$table->dropForeign('defaultLocationId');
        });

        Schema::table(
            'employee_devices',
            function ( $table) {
                //$table->dropForeign('userId');
        });

        Schema::table(
            'allocations',
            function ( $table) {
                //$table->dropForeign('userId');
        });
        */

        $this->forceDropTable($this->tableName);

        Schema::drop('password_reminders');
    }
}
