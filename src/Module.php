<?php

namespace Terranet\Navigation;

use Terranet\Administrator\Collection\Mutable;
use Terranet\Administrator\Columns\Element;
use Terranet\Administrator\Contracts\Module\Editable;
use Terranet\Administrator\Contracts\Module\Exportable;
use Terranet\Administrator\Contracts\Module\Filtrable;
use Terranet\Administrator\Contracts\Module\Navigable;
use Terranet\Administrator\Contracts\Module\Sortable;
use Terranet\Administrator\Contracts\Module\Validable;
use Terranet\Administrator\Form\FormElement;
use Terranet\Administrator\Scaffolding;
use Terranet\Administrator\Traits\Module\AllowFormats;
use Terranet\Administrator\Traits\Module\AllowsNavigation;
use Terranet\Administrator\Traits\Module\HasFilters;
use Terranet\Administrator\Traits\Module\HasForm;
use Terranet\Administrator\Traits\Module\HasSortable;
use Terranet\Administrator\Traits\Module\ValidatesForm;

/**
 * Administrator Resource Navigation
 *
 * @package Terranet\Administrator
 */
class Module extends Scaffolding implements Navigable, Filtrable, Editable, Validable, Sortable, Exportable
{
    use HasFilters, HasForm, HasSortable, ValidatesForm, AllowFormats, AllowsNavigation;

    /**
     * The module Eloquent model
     *
     * @var string
     */
    protected $model = '\App\Menu';

    /**
     * Module title.
     *
     * @return string
     */
    public function title()
    {
        return 'Navigation';
    }

    /**
     * Module navigation.
     *
     * @return string
     */
    public function navigableIn()
    {
        return static::MENU_TOOLS;
    }

    /**
     * Attributes assigned to <a> element.
     *
     * @return mixed
     */
    public function linkAttributes()
    {
        return [
            'icon' => 'fa fa-bars',
        ];
    }

    /**
     * Navigation Columns.
     *
     * @return Mutable
     */
    public function columns()
    {
        return $this->scaffoldColumns()
            ->push($this->links())
            ->updateMany(
                [
                    'name' => function ($name) {
                        return $name->setStandalone(true);
                    },
                    'links' => function ($links) {
                        return $links->setStandalone(true);
                    },
                ]
            )
            ->join(['name', 'links'], 'menu');
    }

    public function form()
    {
        return $this->scaffoldForm()->create(
            FormElement::hidden('ranking')
        );
    }

    /**
     * Provides a collection of navigable items.
     *
     * @return Element
     */
    private function links()
    {
        $links = new Element('links');

        $links->setTemplate(function ($item) {
            $out = [];
            $item->items()->limit(5)->get()
                ->each(function ($menuItem) use (&$out) {
                    $link = $menuItem->assemble();

                    $out[] = link_to($link->url(), $link->title());

                    return $out;
                });

            return implode(' <span class="text-muted">&raquo;</span> ', $out);
        });

        return $links;
    }
}
