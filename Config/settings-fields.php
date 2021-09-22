<?php

return [
  'activateUploadsJob' => [
    'value' => false,
    'name' => 'iad::activateUploadsJob',
    'type' => 'checkbox',
    'props' => [
      'label' => 'Activar Job de Subidas automáticas'
    ]
  ],
  'whatsappTextAnuncio' => [
    'value' => '¡Hola! Quiero conocer mas...',
    'name' => 'iad::whatsappTextAnuncio',
    'type' => 'input',
    'props' => [
      'label' => 'Texto de Mensaje Whatsapp en el Anuncio'
    ]
  ],
  'complaintForm' => [
    'value' => null,
    'name' => 'iad::complaintForm',
    'type' => 'select',
    'loadOptions' => [
      'apiRoute' => 'apiRoutes.qform.forms',
      'select' => ['label' => 'title', 'id' => 'systemName'],
    ],
    'props' => [
      'label' => 'Formulario para Denunciar',
      'multiple' => false,
      'clearable' => true,
    ],
  ],
  'dateInShow' => [
    'value' => false,
    'name' => 'iad::dateInShow',
    'type' => 'checkbox',
    'props' => [
      'label' => 'iad::ads.labelSettingDate'
    ]
  ],
  'selectLayout' => [
    'value' => "iad-list-item-1",
    'name' => 'iad::selectLayout',
    'type' => 'select',
    'columns' => 'col-6',
    'props' => [
      'label' => 'iad::ads.labelSettingLayout',
      'useInput' => false,
      'useChips' => false,
      'multiple' => false,
      'hideDropdownIcon' => true,
      'newValueMode' => 'add-unique',
      'options' => [
        ['label' => 'Layout 1', 'value' => "iad-list-item-1"],
      ]
    ]
  ],
];
