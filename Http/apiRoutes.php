<?php

use Illuminate\Routing\Router;

Route::prefix('ipin/v1')->group(function (Router $router) {
    //======  ADS
    require 'ApiRoutes/adsRoutes.php';

    //======  CATEGORIES
    require 'ApiRoutes/categoriesRoutes.php';

    //======  FIELDS
    //require('ApiRoutes/fieldsRoutes.php');

    //======  SCHEDULES
    //require('ApiRoutes/schedulesRoutes.php');

    //======  UPS
    require 'ApiRoutes/upsRoutes.php';

    //======  ADUPS
    require 'ApiRoutes/adUpsRoutes.php';

    $router->apiCrud([
      'module' => 'iad',
      'prefix' => 'bids',
      'controller' => 'BidApiController',
      'permission' => 'iad.bids',
      //'middleware' => ['create' => [], 'index' => [], 'show' => [], 'update' => [], 'delete' => [], 'restore' => []],
      // 'customRoutes' => [ // Include custom routes if needed
      //  [
      //    'method' => 'post', // get,post,put....
      //    'path' => '/some-path', // Route Path
      //    'uses' => 'ControllerMethodName', //Name of the controller method to use
      //    'middleware' => [] // if not set up middleware, auth:api will be the default
      //  ]
      // ]
    ]);

    //======  Bid Status - STATIC
    $router->apiCrud([
      'module' => 'iad',
      'prefix' => 'bid-statuses',
      'permission' => 'iad.bids',
      'staticEntity' => 'Modules\Iad\Entities\BidStatus'
    ]);

// append

});
