<?php

namespace Terranet\Navigation\Wrappers;

interface NavigationItem
{
    /**
     * Get item identifier.
     *
     * @return mixed|int|string
     */
    public function navigationKey();

    /**
     * Get item title.
     *
     * @return string
     */
    public function navigationTitle();

    /**
     * Build item url.
     *
     * @return string
     */
    public function navigationUrl();

    /**
     * Sortable column.
     *
     * @return string
     */
    public function sortableColumn();
}