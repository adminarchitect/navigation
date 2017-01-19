<?php

namespace Terranet\Navigation;

use Terranet\Administrator\Contracts\Services\TemplateProvider;
use Terranet\Administrator\Services\Template as AdminTemplate;

class Template extends AdminTemplate implements TemplateProvider
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