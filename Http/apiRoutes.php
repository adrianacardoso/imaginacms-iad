<?php

use Illuminate\Routing\Router;


$router->group(['prefix' => 'iad/v1'], function (Router $router) {
  //======  ADS
  require('ApiRoutes/adsRoutes.php');

  //======  CATEGORIES
  require('ApiRoutes/categoriesRoutes.php');

  //======  FIELDS
  require('ApiRoutes/fieldsRoutes.php');

  //======  SCHEDULES
  require('ApiRoutes/schedulesRoutes.php');
});
