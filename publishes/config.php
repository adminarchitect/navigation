<?php

return [
    'providers' => [
        Terranet\Navigation\Providers\PagesProvider::class,
        Terranet\Navigation\Providers\RoutesProvider::class,
        Terranet\Navigation\Providers\LinksProvider::class
    ],

    'paths' => [
        'provider' => "Http/Terranet/Administrator/Navigation/Providers"
    ]
];