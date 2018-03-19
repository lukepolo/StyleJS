<?php

Route::post('logout', 'Auth\LoginController@logout')->name('logout');

/*
|--------------------------------------------------------------------------
| OAuth Routes
|--------------------------------------------------------------------------
|
*/
Route::get('provider/{provider}/link/{repo_access?}', 'Auth\OauthController@link');
Route::get('provider/{provider}/callback', 'Auth\OauthController@getHandleProviderCallback');

/*
|--------------------------------------------------------------------------
| Webhooks
|--------------------------------------------------------------------------
|
*/
Route::group(['prefix' => 'webhook'], function () {
    Route::any('repository/{repositoryHash}/analyze', 'Repository\RepositoryAnalyzeController@analyze');
});

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
|
*/
Route::get('faq', 'PublicController@faq');
Route::get('playground', 'PublicController@playground');
Route::get('repository/{repository}/badge', 'Repository\RepositoryBadgeController@index');

/*
|--------------------------------------------------------------------------
| Prettier Files
|--------------------------------------------------------------------------
|
*/
Route::get('worker.js', function () {
    return response(readfile('https://prettier.io/worker.js'));
});
Route::get('lib/{file}', function ($file) {
    return response(readfile('https://prettier.io/lib/'.$file));
});

/*
|--------------------------------------------------------------------------
| Catchall Route
|--------------------------------------------------------------------------
|
*/
Route::get('{any}', 'Controller@app')->where('any', '.*');
