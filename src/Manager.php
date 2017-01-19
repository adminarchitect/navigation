<?php

namespace Terranet\Navigation;

use App\Menu;

class Manager
{
    public function providers()
    {
        return array_map(function ($provider) {
            return new $provider;
        }, config('navigation.providers', []));
    }

    public function make($id)
    {
        return Menu::whereName($id)
            ->first()
            ->items()
            ->orderBy('rank', 'asc')
            ->get()
            ->map(function ($item) {
                return $item->assemble();
            });
    }
}