<?php

namespace Terranet\Navigation\Providers;

use Illuminate\Support\Collection;
use Terranet\Navigation\URLContainer;
use Terranet\Navigation\Wrappers\Eloquent;
use Terranet\Translatable\Translatable;

abstract class EloquentProvider extends Provider
{
    /**
     * Eloquent model.
     */
    protected $model;

    /**
     * Provides a collection of Navigable elements.
     *
     * @return Collection
     */
    protected function navigable()
    {
        return $this->repository()
            ->get()
            ->map(function ($item) {
                return new Eloquent($item);
            }, []);
    }

    /**
     * Convert stored item to a URLContainer.
     *
     * @param $navigable
     * @return URLContainer
     */
    public function assemble($navigable)
    {
        $builder = $this->find(
            array_get($navigable, 'id')
        );

        return new URLContainer(
            $builder->assemble(),
            $builder->title()
        );
    }

    /**
     * Retrieve page repository.
     * @return mixed
     * @throws Exception
     */
    protected function repository()
    {
        if (! $this->model) {
            throw new Exception(class_basename($this) . ": mandatory property \$model missing.");
        }

        $repo = new $this->model;

        return $repo instanceof Translatable
            ? $repo->translated()->orderBy('tt.title')
            : $repo->orderBy('title');
    }
}