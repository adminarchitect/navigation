<?php

namespace Terranet\Navigation\Providers;

use App\Page;
use Illuminate\Support\Collection;
use Terranet\Navigation\Wrappers\Eloquent;

class PagesProvider extends Provider
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
        return Page::translated()
            ->get()
            ->map(function ($item) {
                return new Eloquent($item);
            }, []);
    }
}