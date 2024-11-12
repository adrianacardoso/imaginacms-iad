<?php

use Illuminate\Routing\Router;

Route::prefix('pins')->group(function (Router $router) {
    $router->post('/', [
        'as' => 'api.iad.ads.create',
        'uses' => 'AdApiController@create',
        'middleware' => ['auth:api'],
    ]);
    $router->get('/', [
        'as' => 'api.iad.ads.index',
        'uses' => 'AdApiController@index',
        'middleware' => ['optional-auth'],
    ]);
    $router->get('/status', [
        'as' => 'api.iad.ads.status.index',
        'uses' => 'AdApiController@indexStatus',
        'middleware' => ['auth:api'],
    ]);
    $router->get('/{criteria}', [
        'as' => 'api.iad.ads.show',
        'uses' => 'AdApiController@show',
        'middleware' => ['optional-auth'],
    ]);
    $router->put('/{criteria}', [
        'as' => 'api.iad.ads.update',
        'uses' => 'AdApiController@update',
        'middleware' => ['auth:api'],
    ]);
    $router->delete('/{criteria}', [
        'as' => 'api.iad.ads.delete',
        'uses' => 'AdApiController@delete',
        'middleware' => ['auth:api'],
    ]);
});
