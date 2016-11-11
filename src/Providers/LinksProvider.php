<?php

namespace Terranet\Navigation\Providers;

use Illuminate\Support\Collection;

class LinksProvider extends Provider
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
     * Provides a collection of Navigable elements.
     *
     * @return Collection
     */
    protected function navigable()
    {
        return collect([]);
    }
}