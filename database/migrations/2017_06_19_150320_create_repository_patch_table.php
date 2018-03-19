<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepositoryPatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repository_patches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('branch')->nullable();
            $table->string('patch_branch')->nullable();
            $table->integer('repository_id');
            $table->string('status')->default('queued');
            $table->string('runtime')->nullable();
            $table->longText('log')->nullable();
            $table->string('sha')->nullable();
            $table->timestamps();
        });

        Schema::table('repository_patches', function (Blueprint $table) {
            $table->index([
                'repository_id',
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
        Schema::dropIfExists('repository_patches');
    }
}
