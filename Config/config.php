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


  /*Layout Products - Index */
  'layoutIndex' => [
    'default' => 'masonry',
    'options' => [
      'four' => [
        'name' => 'four',
        'class' => 'col-6 col-md-4 col-lg-3',
        'icon' => 'fa fa-th-large',
        'status' => true
      ],
      'three' => [
        'name' => 'three',
        'class' => 'col-6 col-md-4 col-lg-4',
        'icon' => 'fa fa-square-o',
        'status' => true
      ],
      'one' => [
        'name' => 'one',
        'class' => 'col-12',
        'icon' => 'fa fa-align-justify',
        'status' => true
      ],
      'masonry' => [
        'name' => 'masonry',
        'class' => 'card',
        'icon' => 'fa fa-align-justify',
        'status' => true,
        'wrapperClass' => 'card-columns'
      ]
    ]
  ],

  /*
  |--------------------------------------------------------------------------
  | Filter location range
  |--------------------------------------------------------------------------
  */
  'location-range' => [
      'title' => 'Location Rango',
      'name' => 'location-range',
      'status' => true,
      'isExpanded' => true,
      'type' => 'location',
      'repository' => 'Modules\Iad\Repositories\AdRepository',
      'emitTo' => 'filtersGetData',//Emit data selected (Parent Component to this case)
      'repoAction' => 'filter', //Action in repo ('filter' in Ad Repository)
      'repoAttribute' => 'nearby',
      'listener' => null, // Listen to another component
      'repoMethod' => null,
      'layout' => 'location-layout-1', // geolocalization and range
      'classes' => 'col-xs-12 col-md-6', // Main Class Filter (Columns and others classes)
      'radio' => [
          'measure' => 'km',
          'values' => [0,1,3,5,10,25,50],
          'defaultValue' => 0
      ] 
  ],


  /*
  |--------------------------------------------------------------------------
  | Filters to the index page
  |--------------------------------------------------------------------------
  */
  'filters' => [
    'range-prices' => [
      'title' => 'Rango de Precios',
      'name' => 'range-prices',
      'status' => true,
      'isExpanded' => true,
      'type' => 'range',
      'repository' => 'Modules\Iad\Repositories\AdRepository',
      'emitTo' => 'filtersGetData',//Emit data selected (Parent Component to this case)
      'repoAction' => 'filter', //Action in repo ('filter' in Ad Repository)
      'repoAttribute' => 'priceRange',
      'listener' => null, // Listen to another component
      'repoMethod' => null,
      'layout' => 'range-layout-2', // Two inputs (Min and Max)
      'classes' => 'col-xs-12 col-md-6' // Main Class Filter (Columns and others classes)
    ],
    'range-ages' => [
      'title' => 'Rango de Edades',
      'name' => 'range-ages',
      'status' => true,
      'isExpanded' => true,
      'type' => 'range',
      'repository' => 'Modules\Iad\Repositories\AdRepository',
      'emitTo' => 'filtersGetData',//Emit data selected (Parent Component to this case)
      'repoAction' => 'filter',
      'repoAttribute' => 'ageRange',
      'listener' => null, // Listen to another component
      'repoMethod' => null,
      'layout' => 'range-layout-2', // Two inputs (Min and Max)
      'classes' => 'col-xs-12 col-md-6' // Main Class Filter (Columns and others classes)
    ],
    'ads-categories' => [
      'title' => 'Categorias',
      'name' => 'ads-categories',
      'status' => true,
      'isExpanded' => true,
      'type' => 'checkbox',
      'repository' => 'Modules\Iad\Repositories\CategoryRepository',
      'emitTo' => 'filtersGetData', //Emit data selected (Parent Component to this case)
      'repoAction' => 'filter',
      'repoAttribute' => 'categories',
      'listener' => null,  // Listen to another component
      'repoMethod' => null, // Method to get data - getItemsBy (default)
      'layout' => 'checkbox-layout-3', //Layout with components childrens
      'classes' => 'parent-ads-categories', // Main Class Filter (Not column to this case)
      'wrapperClasses' => 'card-columns', // Class to group the children (Filters)
      'childrenClasses' => 'card' // Class to each children (Filter)
    ]
    
  ],
  
  //Media Fillables
  'mediaFillable' => [
    'ad' => [
      'mainimage' => 'single',
      'secondaryimage' => 'single',
      'gallery' => 'multiple'
    ],
    'category' => [
      'mainimage' => 'single',
      'secondaryimage' => 'single'
    ]
  ]

];
