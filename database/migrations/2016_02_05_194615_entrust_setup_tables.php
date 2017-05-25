<?php

use Illuminate\Database\Migrations\Migration;

class EntrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('assigned_roles');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('scope_permission');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');      
        Schema::dropIfExists('scopes');

        // Create table for storing roles
        Schema::create('roles', function ($table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users (Many-to-Many)
        Schema::create('role_user', function ($table) {
            $table->integer('user_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id']);
        });

        // Create table for storing permissions
        Schema::create('permissions', function ($table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function ($table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
        // Create table for storing scope
        Schema::create('scopes', function ($table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
         // Create table for associating scopes to permissions (Many-to-Many)
        Schema::create('scope_permission', function ($table) {
            $table->integer('scope_id')->unsigned();
            $table->integer('permission_id')->unsigned();
            $table->foreign('scope_id')->references('id')->on('scopes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['permission_id', 'scope_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('permission_role');
        Schema::drop('scope_permission');
        Schema::drop('scopes');
        Schema::drop('permissions');
        Schema::drop('role_user');
        Schema::drop('roles');       
        

    }
}
