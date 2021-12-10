<?php

//Options to age
$optionsAge = [];
for ($i = 18; $i <= 60; $i++) $optionsAge[] = ['label' => $i, 'value' => $i];

return [
  //Extra field to crud ads
  'ads' => [
    'name' => [
      'value' => null,
      'name' => 'name',
      'type' => 'input',
      'required' => true,
      'fakeFieldName' => 'fields',
      'props' => [
        'label' => 'Nombre*',
      ]
    ],
    'age' => [
      'value' => 18,
      'name' => 'age',
      'type' => 'select',
      'fakeFieldName' => 'fields',
      'props' => [
        'label' => 'Edad',
        'options' => $optionsAge
      ]
    ],
    'linkExperiences' => [
      'value' => [],
      'name' => 'linkExperiences',
      'type' => 'select',
      'fakeFieldName' => 'options',
      'props' => [
        'label' => 'Experiencias de tus clientes',
        'hint' => 'Si tienes experiencias o reseñas publicadas por catadores en foros de prepagos como ForoPrepagosColombia, DonColombia, etc. y quieres que las enlacemos desde tu anuncio, indica las direcciones web',
        'useInput' => true,
        'useChips' => true,
        'multiple' => true,
        'hideDropdownIcon' => true,
        'newValueMode' => 'add-unique',
        'clearable' => true
      ]
    ],
    'squareMeter' => [
      'value' => null,
      'name' => 'squareMeter',
      'type' => 'input',
      'required' => true,
      'isFakeField' => true,
      "fakeFieldName" => "options",
      'props' => [
        'label' => 'Metros Cuadrados*',
       
      ]
    ],
    
    'bedrooms' => [
      'value' => null,
      'name' => 'bedrooms',
      'type' => 'input',
      'required' => true,
      'isFakeField' => true,
      "fakeFieldName" => "options",
      'props' => [
        'label' => 'Numero de Habitaciones*',
        
      ]
    ],
    'toilets' => [
      'value' => null,
      'name' => 'toilets',
      'type' => 'input',
      'required' => true,
      'isFakeField' => true,
      "fakeFieldName" => "options",
      'props' => [
        'label' => 'Numeros de Baños*',
       
      ]
    ],
    'parking' => [
      'value' => null,
      'name' => 'parking',
      'type' => 'input',
      'required' => true,
      'isFakeField' => true,
      "fakeFieldName" => "options",
      'props' => [
        'label' => 'Espacio para Autos*',
     
      ]
    ],
    'typoSale' => [
      'value' => null,
      'name' => 'typoSale',
      'type' => 'select',
      'required' => false,
      'isFakeField' => true,
      "fakeFieldName" => "options",
      'props' => [
        'label' => 'Tipo de anuncio',
        'options' => $optionsSale
      ]
    ],
  ],
  'category' => [
      'sortOrder' => [
          'value' => 0,
          'name' => 'sortOrder',
          'type' => 'input',
          'props' => [
              'label' => 'Orden',
              'type' => 'number',
              'min' => '0'
          ]
      ]
  ]
];
