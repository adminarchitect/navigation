<?php

namespace Terranet\Navigation\Providers;

use App\Page;

class PagesProvider extends EloquentProvider
{
    protected $model = Page::class;
}