<?php

namespace Terranet\Navigation;

class Manager
{
    public function providers()
    {
        return array_map(function ($provider) {
            return new $provider;
        }, config('navigation.providers', []));
    }
}