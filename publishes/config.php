<?php

return [
    'providers' => [
        #\App\Http\Terranet\Administrator\Navigation\Providers\PagesProvider::class,
        \App\Http\Terranet\Administrator\Navigation\Providers\RoutesProvider::class,
        \App\Http\Terranet\Administrator\Navigation\Providers\LinksProvider::class,
    ],

    'paths' => [
        'provider' => "Http/Terranet/Administrator/Navigation/Providers"
    ],
    
    # Routes under these prefixes will be skipped from navigation module.
    'skip' => [
        'cms', 'horizon', 'api', 'auth'
    ],
];
