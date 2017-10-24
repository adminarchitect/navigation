<?php

namespace Terranet\Navigation;

class URLContainer
{
    protected $url;

    protected $title;

    protected $navigable;

    public function __construct($url, $title)
    {
        $this->url = $url;
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return $this->title;
    }

    public function navigable()
    {
        return $this->navigable;
    }

    public function setNavigable($navigable)
    {
        $this->navigable = $navigable;

        return $this;
    }
}