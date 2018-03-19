<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('user', 'UserController', [
        'only' => ['store', 'destroy'],
    ]);

    Route::apiResource('repositories', 'RepositoriesController', [
        'only' => ['index', 'show', 'update', 'destroy'],
    ]);

    Route::apiResource('remote-repositories', 'RemoteRepositoriesController', [
        'only' => ['index', 'store'],
    ]);

    Route::apiResource('repository.patches', 'Repository\RepositoryPatchesController', [
        'only' => ['index', 'show'],
    ]);

    Route::apiResource('repository.branches', 'Repository\RepositoryBranchesController', [
        'only' => ['index'],
    ]);

    Route::post('repository/{repository}/repair/webhook', 'Repository\RepositoryWebHooksController@store');
});
