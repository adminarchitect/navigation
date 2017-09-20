<?php

namespace Terranet\Navigation\Providers;

use Illuminate\Contracts\Support\Arrayable;
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
     * Restore item from database and prepare for editing.
     *
     * @param Arrayable $model
     * @return mixed
     */
    public function refresh(Arrayable $model)
    {
        $key = array_get($model->navigable, 'id');

        return array_merge($model->toArray(), [
            'provider' => $this->name(),
            'object' => new Eloquent(
                $this->find($key)->getObject()
            ),
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
        $builder = $this->find(
            array_get($navigable['navigable'], 'id')
        );

        return new URLContainer(
            $builder->assemble(),
            $builder->title()
        );
    }

    /**
     * Retrieve page repository.
     *
     * @return mixed
     * @throws Exception
     */
    protected function repository()
    {
        if (!$this->model) {
            throw new Exception(class_basename($this) . ": mandatory property \$model missing.");
        }

        $repo = new $this->model;
        $sortable = $repo->sortableColumn();

        return $repo instanceof Translatable
            ? $repo->translated()->orderBy(is_a($sortable, Expression::class) ? $sortable : 'tt.' . $sortable)
            : $repo->orderBy($sortable);
    }
}
