<?php

namespace Terranet\Navigation;

class URLContainer
{
    protected $uri;

    protected $title;

    public function __construct($uri, $title)
    {
        $this->uri = $uri;
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return $this->title;
    }
}