<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyUserImportJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_user_import_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id', false, true);
            $table->string('path');
            $table->string('file');
            $table->integer('total', false, true);
            $table->integer('created', false, true)->default(0);
            $table->integer('updated', false, true)->default(0);
            $table->integer('failed', false, true)->default(0);
            $table->text('fields');
            $table->text('sample');
            $table->text('mappings');
            $table->tinyInteger('status', false, true)->default(0);
            $table->integer('created_by_id', false, true);
            $table->integer('updated_by_id', false, true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_user_import_jobs');
    }
}
