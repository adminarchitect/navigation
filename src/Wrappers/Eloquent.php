<?php

namespace Terranet\Navigation\Wrappers;

class Eloquent implements Wrapper
{
    private $object;

    public function __construct(NavigationItem $model)
    {
        $this->object = $model;
    }

    /**
     * Get element Unique id.
     *
     * @return mixed
     */
    public function id()
    {
        return $this->object->navigationKey();
    }

    /**
     * Get element title.
     *
     * @return mixed
     */
    public function title()
    {
        return $this->object->navigationTitle();
    }

    /**
     * Assemble element URL.
     *
     * @return mixed
     */
    public function assemble()
    {
        return $this->object->navigationUrl();
    }

    public function getObject()
    {
        return $this->object;
    }

    /**
     * Get data that should be stored to database.
     *
     * @return mixed
     */
    public function toArray()
    {
        return [
            'type' => get_class($this->object),
            'id' => $this->id(),
        ];
    }
}
