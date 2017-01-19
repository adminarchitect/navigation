<?php

namespace Terranet\Navigation\Models;

use App\Http\Terranet\Administrator\Navigation\Providers\LinksProvider;
use App\Http\Terranet\Administrator\Navigation\Providers\RoutesProvider;
use Illuminate\Database\Eloquent\Model;
use Terranet\Navigation\Wrappers\NavigationItem;

class MenuItem extends Model
{
    const PARENT_KEY = 'parent_id';

    public $timestamps = false;

    protected $fillable = [
        'menu_id',
        'parent_id',
        'rank',
        'navigable',
    ];

    protected $casts = [
        'navigable' => 'json',
    ];

    public function refresh()
    {
        $provider = app()->make($this->navigable['provider']);

        switch (get_class($provider)) {
            case RoutesProvider::class:
            case LinksProvider::class:
                return static::forceFill($provider->refresh($this));
        }

        if ($type = array_get($this->navigable, 'type')) {
            $type = app()->make($type);

            if ($type instanceof NavigationItem) {
                return static::forceFill($provider->refresh($this));
            }
        }

        return null;
    }

    public function assemble()
    {
        $provider = app()->make($this->navigable['provider']);

        switch (get_class($provider)) {
            case RoutesProvider::class:
            case LinksProvider::class:
                return $provider->assemble($this);
        }

        if ($type = array_get($this->navigable, 'type')) {
            $type = app()->make($type);

            if ($type instanceof NavigationItem) {
                return $provider->assemble($this);
            }
        }

        return null;
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
                    ->where(static::PARENT_KEY, (int) $this->id)
                    ->orderBy('rank', 'asc')
                    ->get()
                    ->map(function (MenuItem $item) {
                        return $item->refresh();
                    });
    }
}
