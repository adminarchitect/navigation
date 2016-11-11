<?php

namespace Terranet\Navigation\Wrappers;

interface NavigationItem
{
    public function navigationKey();

    public function navigationTitle();

    public function navigationUrl();
}