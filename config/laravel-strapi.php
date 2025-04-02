<?php

return [

    'base_url' => env('STRAPI_BASE_URL', 'http://localhost:1337'),

    'components' => [
        // 'Content' => [
        //     App\Strapi\Components\Content\Text::class,
        // ],
    ],

    'caching' => [
        'active' => env('STRAPI_CACHE_ACTIVE', true),
        'ttl' => env('STRAPI_CACHE_TTL', 3600), // 1 hour
    ],

];
