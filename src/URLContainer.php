<?php

namespace Terranet\Navigation;

class URLContainer
{
    protected $url;

    protected $title;

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
}