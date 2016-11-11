<?php

namespace App\Http\Terranet\Administrator\Navigation\Providers;

use App\Page;
use Illuminate\Support\Collection;
use Terranet\Navigation\Provider;
use Terranet\Navigation\Wrappers\Eloquent;
use Terranet\Translatable\Translatable;

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
        return $this->repository()
            ->get()
            ->map(function ($item) {
                return new Eloquent($item);
            }, []);
    }

    /**
     * Retrieve page repository.
     *
     * @return mixed
     */
    protected function repository()
    {
        $repo = new Page;
        return $repo instanceof Translatable
            ? $repo->translated()->orderBy('tt.title')
            : $repo->orderBy('title');
    }
}