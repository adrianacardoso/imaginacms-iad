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
];
