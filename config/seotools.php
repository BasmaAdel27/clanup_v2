<?php
return [
    'meta' => [
        'defaults'       => [
            'title'        => env('APPLICATION_NAME', 'Network'),
            'titleBefore'  => false,
            'description'  => false,
            'separator'    => ' - ',
            'keywords'     => [],
            'canonical'    => false,
            'robots'       => 'index, follow',
        ],
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],
        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        'defaults' => [
            'title'       => env('APPLICATION_NAME', 'Network'),
            'description' => false,
            'url'         => false,
            'type'        => false,
            'site_name'   => false,
            'images'      => [],
        ],
    ],
    'twitter' => [
        'defaults' => [
            //
        ],
    ],
    'json-ld' => [
        'defaults' => [
            'title'       => env('APPLICATION_NAME', 'Network'),
            'description' => false,
            'url'         => false,
            'type'        => 'Organization',
            'images'      => [],
        ],
    ],
];
