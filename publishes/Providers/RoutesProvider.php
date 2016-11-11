<?php

namespace App\Http\Terranet\Administrator\Navigation\Providers;

use Illuminate\Support\Collection;
use Terranet\Navigation\Provider;
use Terranet\Navigation\Wrappers\Route;

class RoutesProvider extends Provider
{
    /**
     * Provider name.
     *
     * @return mixed
     */
    public function name()
    {
        $name = str_replace('Provider', '', class_basename($this));
        $key = "navigation::providers." . $name;

        return app('translator')->has($key) ? trans($key) : $name;
    }

    /**
     * @return Collection
     */
    protected function navigable()
    {
        $routes = app('router')->getRoutes()->getRoutesByMethod()["GET"];

        return $this->collectRoutes($routes)
            ->map(function ($item) {
                return new Route($item->getAction()['as'], []);
            });
    }

    /**
     * @param $routes
     * @return Collection
     */
    protected function collectRoutes($routes)
    {
        return collect($routes)
            ->filter($this->onlyIndexRoutes());
    }

    /**
     * @return \Closure
     */
    protected function onlyIndexRoutes()
    {
        return function ($route) {
            $action = $route->getAction();

            if (!is_null($action['prefix'])) {
                return false;
            }

            return str_contains($name = array_get($action, 'as'), '.index') || 'home' == $name;
        };
    }
}