<?php

if (!function_exists('nestable_menu')) {
    function nestable_menu($menu)
    {
        $out[] = '<ol class="dd-list">';
        foreach ($menu as $link) {
            $out[] = '<li class="dd-item" data-id="' . $link->id . '">';
            $out[] = '' .
                '<div class="dd-handle">' .
                '   <a href="#" class="remove-navigable pull-right" style="margin-left: 10px;">&times;</a>' .
                '   <span class="text-muted pull-right" data-template="provider">' . $link->provider . ' </span>' .
                '   <strong class="pull-left" data-template="title">' . $link->object->title() . '</strong>' .
                '   <div class="clearfix"></div>' .
                '</div>';

            if (with($children = $link->children())->count()) {
                $out[] = nestable_menu($children);
            }

            $out[] = '</li>';
        }
        $out[] = '</ol>';

        return implode(PHP_EOL, $out);
    }
}