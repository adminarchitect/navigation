<?php

namespace Terranet\Navigation;

use Illuminate\Support\Collection;
use IteratorAggregate;

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
    abstract public function name();

    /**
     * Provides a collection of Navigable elements.
     *
     * @return Collection
     */
    abstract protected function navigable();

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