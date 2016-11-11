<?php

namespace App;

use App\Http\Terranet\Administrator\Navigation\Providers\LinksProvider;
use App\Http\Terranet\Administrator\Navigation\Providers\RoutesProvider;
use Illuminate\Database\Eloquent\Model;
use Terranet\Navigation\URLContainer;
use Terranet\Navigation\Wrappers\Eloquent;
use Terranet\Navigation\Wrappers\Link;
use Terranet\Navigation\Wrappers\NavigationItem;
use Terranet\Navigation\Wrappers\Route;

class MenuItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'menu_id', 'parent_id', 'rank', 'navigable',
    ];

    protected $casts = [
        'navigable' => 'json',
    ];

    public function refresh()
    {
        $provider = app()->make($this->navigable['provider']);

        if ($provider instanceof RoutesProvider) {
            return array_merge($this->toArray(), [
                'provider' => $provider->name(),
                'object' => new Route($this->navigable['id'], $this->navigable['params']),
            ]);
        }

        if ($provider instanceof LinksProvider) {
            return array_merge($this->toArray(), [
                'provider' => $provider->name(),
                'object' => new Link($this->navigable['url'], $this->navigable['title']),
            ]);
        }

        if ($type = array_get($this->navigable, 'type')) {
            $type = app()->make($type);
            $key = array_get($this->navigable, 'id');

            if ($type instanceof NavigationItem) {
                return array_merge($this->toArray(), [
                    'provider' => $provider->name(),
                    'object' => new Eloquent(
                        $provider->find($key)->getObject()
                    ),
                ]);
            }
        }

        return null;
    }

    public function assemble()
    {
        $provider = app()->make($this->navigable['provider']);

        if ($provider instanceof RoutesProvider) {
            $builder = new Route($this->navigable['id'], $this->navigable['params']);

            return new URLContainer(
                $builder->assemble(),
                trans('navigation.' . $this->navigable['id'] . '.title')
            );
        }

        if ($provider instanceof LinksProvider) {
            $builder = new Link($this->navigable['url'], $this->navigable['title']);

            return new URLContainer(
                $builder->assemble(),
                $this->navigable['title']
            );
        }

        if ($type = array_get($this->navigable, 'type')) {
            $type = app()->make($type);

            if ($type instanceof NavigationItem) {
                $builder = $provider->find(
                    array_get($this->navigable, 'id')
                );

                return new URLContainer(
                    $builder->assemble(),
                    $builder->title()
                );
            }
        }

        return null;
    }
}
