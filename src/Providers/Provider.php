<?php

namespace Terranet\Navigation\Providers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use IteratorAggregate;
use Terranet\Navigation\URLContainer;

abstract class Provider implements IteratorAggregate
{
    /**
     * Unique ID attribute.
     *
     * @var string
     */
    protected $key = "id";

    /**
     * Title attribute.
     *
     * @var string
     */
    protected $title = "title";

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
     * Provides a collection of Navigable elements.
     *
     * @return Collection
     */
    abstract protected function navigable();

    /**
     * Convert stored item to a URLContainer.
     *
     * @param $navigable
     * @return URLContainer
     */
    abstract public function assemble($navigable);

    /**
     * Restore item from database and prepare for editing.
     *
     * @param Arrayable $model
     * @return mixed
     */
    abstract public function refresh(Arrayable $model);

    /**
     * Finds a collection element by id().
     *
     * @param $key
     * @return mixed
     */
    public function find($key)
    {
        return $this->navigable()
            ->first(function ($item) use ($key) {
                return $item->id() == $key;
            });
    }

    /**
     * Prepares collection for navigation builder.
     *
     * @return Collection
     */
    public function getIterator()
    {
        return collect($this->navigable());
    }
}