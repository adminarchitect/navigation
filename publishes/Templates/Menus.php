<?php

namespace App\Http\Terranet\Administrator\Templates;

use Terranet\Administrator\Contracts\Services\TemplateProvider;
use Terranet\Administrator\Services\Template;

class Menus extends Template implements TemplateProvider
{
    /**
     * Scaffold edit templates
     *
     * @param $partial
     * @return mixed array|string
     */
    public function edit($partial = 'index')
    {
        $partials = array_merge(parent::edit(null), [
            'index' => 'navigation::edit.index'
        ]);

        return (null === $partial ? $partials : $partials[$partial]);
    }
}