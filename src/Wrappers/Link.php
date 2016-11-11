<?php

namespace Terranet\Navigation\Wrappers;

class Link implements Wrapper
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $title;

    public function __construct($url, $title = null)
    {
        $this->url = $url;
        $this->title = $title ?: $url;
    }

    /**
     * Get element Unique id.
     *
     * @return mixed
     */
    public function id()
    {
        return $this->url;
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
     * Assemble element URL.
     *
     * @return mixed
     */
    public function assemble()
    {
        return url($this->url);
    }

    /**
     * Get data that should be stored to database.
     *
     * @return mixed
     */
    public function toArray()
    {
        return [
            'url' => $this->url,
            'title' => $this->title,
        ];
    }
}