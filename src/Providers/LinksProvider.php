<?php

namespace Terranet\Navigation\Providers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Terranet\Navigation\URLContainer;
use Terranet\Navigation\Wrappers\Link;

class LinksProvider extends Provider
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
            'object' => new Link($model->navigable['url'], $model->navigable['title']),
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
        $builder = new Link($navigable['url'], $navigable['title']);

        return new URLContainer(
            $builder->assemble(),
            $this->titleCase($navigable['title'])
        );
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