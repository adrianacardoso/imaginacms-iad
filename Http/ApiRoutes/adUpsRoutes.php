<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => 'pin-ups'], function (Router $router) {

  $router->get('/', [
    'as' => 'api.iad.ups.index',
    'uses' => 'AdUpApiController@index',
  ]);
  $router->get('/{criteria}', [
    'as' => 'api.iad.ups.show',
    'uses' => 'AdUpApiController@show',
  ]);

});
