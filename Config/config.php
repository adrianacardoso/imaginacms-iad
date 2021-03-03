<?php

return [
  'name' => 'Iad',
  'fields' => [
    "name",
    "age",
    "twitter",
    "whatsapp",
    "independent_escort",
    "note",
    "phone",
    "customer_feedback",
    "address",
    "mainImage",
  ],
  //add: product relations like users relations style
  'relations' => [
    'ad' => [
      'rates' => function () {
        return $this->belongsToMany(Rate::class, 'icustom__rate_ad')->withPivot('price');
      },
    ]
  ],
  'imagesize' => ['width' => 800, 'height' => 800],
  'mediumthumbsize' => ['width' => 400, 'height' => 400],
  'smallthumbsize' => ['width' => 100, 'height' => 100],

  /*
  |--------------------------------------------------------------------------
  | Filters to the index page
  |--------------------------------------------------------------------------
  */
  'filters' => [
    /*
    'range-prices' => [
      'title' => 'icommerce::common.range.title',
      'name' => 'range-prices',
      'status' => true,
      'isExpanded' => true,
      'type' => 'range',
      'repository' => 'Modules\Iad\Repositories\AdRepository',
      'emitTo' => 'itemsListGetData',
      'repoAction' => 'filter',
      'repoAttribute' => 'priceRange',
      'listener' => 'itemListRendered',
      'repoMethod' => 'getPriceRange',
      'layout' => 'range-layout-2',
      'classes' => 'col-xs-12 col-md-6',
      'step' => 10000
    ],
    */
    'contenido' => [
      'title' => 'Contenido',
      'name' => 'contenido',
      'status' => true,
      'isExpanded' => true,
      'type' => 'checkbox',
      'repository' => 'Modules\Iad\Repositories\CategoryRepository',
      'emitTo' => 'itemsListGetData',
      'repoAction' => 'filter',
      'repoAttribute' => 'categories',
      'listener' => 'itemListRendered',
      'repoMethod' => null,
      'params' => ['filter'=> ['parentId' => 61] ],
      'layout' => 'checkbox-layout-2',
      'classes' => 'card'
    ],
    'etnia' => [
      'title' => 'Etnia',
      'name' => 'etnia',
      'status' => true,
      'isExpanded' => true,
      'type' => 'checkbox',
      'repository' => 'Modules\Iad\Repositories\CategoryRepository',
      'emitTo' => 'itemsListGetData',
      'repoAction' => 'filter',
      'repoAttribute' => 'categories',
      'listener' => 'itemListRendered',
      'repoMethod' => null,
      'params' => ['filter'=> ['parentId' => 12] ],
      'layout' => 'checkbox-layout-2',
      'classes' => 'card'
    ],
    'special-servicies' => [
      'title' => 'Servicios Especiales',
      'name' => 'special-services',
      'status' => true,
      'isExpanded' => true,
      'type' => 'checkbox',
      'repository' => 'Modules\Iad\Repositories\CategoryRepository',
      'emitTo' => 'itemsListGetData',
      'repoAction' => 'filter',
      'repoAttribute' => 'categories',
      'listener' => 'itemListRendered',
      'repoMethod' => null,
      'params' => ['filter'=> ['parentId' => 3] ],
      'layout' => 'checkbox-layout-2',
      'classes' => 'card'
    ],
    'lugar' => [
      'title' => 'Lugar',
      'name' => 'lugar',
      'status' => true,
      'isExpanded' => true,
      'type' => 'checkbox',
      'repository' => 'Modules\Iad\Repositories\CategoryRepository',
      'emitTo' => 'itemsListGetData',
      'repoAction' => 'filter',
      'repoAttribute' => 'categories',
      'listener' => 'itemListRendered',
      'repoMethod' => null,
      'params' => ['filter'=> ['parentId' => 4] ],
      'layout' => 'checkbox-layout-2',
      'classes' => 'card'
    ],
    'country' => [
      'title' => 'Pais',
      'name' => 'country',
      'status' => true,
      'isExpanded' => true,
      'type' => 'select',
      'repository' => 'Modules\Icommerce\Repositories\ProductRepository',
      'emitTo' => 'itemsListGetData',
      'repoAction' => 'filter',
      'repoAttribute' => null,
      'listener' => 'itemListRendered',
      'repoMethod' => null,
      'layout' => 'select-layout-1',
      'classes' => 'card'
    ],
    

  ]

];
