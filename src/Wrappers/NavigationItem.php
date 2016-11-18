<?php

namespace Terranet\Navigation;

interface NavigationItem
{
    public function navigationKey();

    public function navigationTitle();

    public function navigationUrl();
}