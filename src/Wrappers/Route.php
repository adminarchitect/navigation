<?php

namespace Terranet\Navigation\Wrappers;

class Route implements Wrapper
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $params;

    /**
     * @var string
     */
    private $title;

    public function __construct($name, array $params = [])
    {
        $this->name = $name;
        $this->params = $params;

        $this->setTitle(
            app('translator')->has($key = 'navigation::routes.' . $this->id())
            ? trans($key)
            : title_case(str_replace('.index', '', $this->id()))
        );
    }

    /**
     * Get element Unique id.
     *
     * @return mixed
     */
    public function id()
    {
        return $this->name;
    }

    /**
     * Get element title.
     *
     * @return mixed
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * Get element title.
     *
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Assemble element URL.
     *
     * @return mixed
     */
    public function assemble()
    {
        return route($this->name, $this->params);
    }

    /**
     * Get data that should be stored to database.
     *
     * @return mixed
     */
    public function toArray()
    {
        return [
            'type' => get_class($this),
            'id' => $this->id(),
            'params' => $this->params
        ];
    }
}