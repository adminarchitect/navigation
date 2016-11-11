<?php
namespace Terranet\Navigation\Wrappers;

interface Wrapper
{
    /**
     * Get element Unique id.
     *
     * @return mixed
     */
    public function id();

    /**
     * Get element title.
     *
     * @return mixed
     */
    public function title();

    /**
     * Assemble element URL.
     *
     * @return mixed
     */
    public function assemble();

    /**
     * Get data that should be stored to database.
     *
     * @return mixed
     */
    public function toArray();
}