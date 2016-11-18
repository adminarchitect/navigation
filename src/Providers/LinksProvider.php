<?php

namespace Terranet\Navigation\Providers;

use Illuminate\Support\Collection;
use Terranet\Navigation\URLContainer;
use Terranet\Navigation\Wrappers\Link;

class LinksProvider extends Provider
{
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
            $navigable['title']
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