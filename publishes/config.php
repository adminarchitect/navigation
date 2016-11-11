<?php

return [
    'providers' => [
        \App\Http\Terranet\Administrator\Navigation\Providers\PagesProvider::class,
        \App\Http\Terranet\Administrator\Navigation\Providers\RoutesProvider::class,
        \App\Http\Terranet\Administrator\Navigation\Providers\LinksProvider::class,
        \App\Http\Terranet\Administrator\Navigation\Providers\PartnersProvider::class,
    ],

    'paths' => [
        'provider' => "Http/Terranet/Administrator/Navigation/Providers"
    ]
];