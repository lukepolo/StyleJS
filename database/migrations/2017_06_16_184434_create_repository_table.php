<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepositoryTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('repository');
            $table->integer('repository_id');
            $table->string('default_branch')->nullable();
            $table->integer('user_repository_provider_id');
            $table->integer('automatic_deployment_id')->nullable();
            $table->json('branches')->nullable();
            $table->json('file_types')->nullable();
            $table->json('cli_options')->nullable();
            $table->boolean('no_ci')->default(false);
            $table->boolean('on_demand')->default(false);
            $table->string('analysis_setting')->nullable();
            $table->longText('ignore_directories')->nullable();
            $table->longText('include_directories')->nullable();
            $table->timestamps();
        });


        Schema::table('repositories', function (Blueprint $table) {
            $table->index([
                'user_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repositories');
    }
}
