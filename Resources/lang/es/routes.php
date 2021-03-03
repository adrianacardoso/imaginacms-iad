<?php

return [

  'ad' => [
    'index' => [
      'index' => 'anuncios',
      'category' => 'anuncios/c/{categorySlug}',
      'service' => 'anuncios/s/{serviceSlug}',
    ],

    'show' => [
      'ad' => 'anuncio/{adSlug}',
    ],

    'create' => [
      'ad' => 'crear/anuncio',
    ],
    'edit' => [
      'ad' => 'editar/anuncio/{adId}',
    ],

  ],
];
