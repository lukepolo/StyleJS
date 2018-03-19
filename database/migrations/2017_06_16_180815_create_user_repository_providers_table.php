<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserRepositoryProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_repository_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('repository_provider_id');
            $table->string('provider_id');
            $table->longText('token');
            $table->longText('refresh_token')->nullable();
            $table->string('expires_in')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('user_repository_providers', function (Blueprint $table) {
            $table->index([
                'provider_id',
                'repository_provider_id',
            ], 'oauth_indices');

            $table->index([
                'user_id',
            ]);

            $table->index([
                'provider_id',
            ]);

            $table->index([
                'repository_provider_id',
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
        \Schema::dropIfExists('user_repository_providers');
    }
}
