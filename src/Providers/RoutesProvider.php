<?php

namespace Terranet\Navigation\Providers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Terranet\Navigation\URLContainer;
use Terranet\Navigation\Wrappers\Route;

class RoutesProvider extends Provider
{
    /**
     * Restore item from database and prepare for editing.
     *
     * @param Arrayable $model
     * @return array|null
     */
    public function refresh(Arrayable $model)
    {
        return array_merge($model->toArray(), [
            'provider' => $this->name(),
            'object' => new Route($model->navigable['id'], $model->navigable['params']),
        ]);
    }

    /**
     * Convert stored item to a URLContainer.
     *
     * @param $navigable
     * @return URLContainer
     */
    public function assemble($navigable)
    {
        $builder = new Route($navigable['id'], $navigable['params']);

        return new URLContainer(
            $builder->assemble(),
            trans('navigation.' . $navigable['id'] . '.title')
        );
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