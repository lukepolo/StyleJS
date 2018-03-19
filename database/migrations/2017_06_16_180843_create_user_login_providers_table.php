<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoginProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('provider');
            $table->string('provider_id');
            $table->longText('token');
            $table->longText('refresh_token')->nullable();
            $table->string('expires_in')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('user_login_providers', function (Blueprint $table) {
            $table->index([
                'provider',
                'provider_id',
            ], 'oauth_indices');

            $table->index([
                'provider_id',
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
        Schema::dropIfExists('user_login_providers');
    }
}
