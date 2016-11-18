<?php

namespace Terranet\Navigation\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model implements \IteratorAggregate
{
    use Sluggable;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('rank', 'asc');
    }

    public function getIterator()
    {
        return $this->items->map(function (MenuItem $item) {
            return $item->refresh();
        });
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'name' => [
                'source' => 'name',
                'onUpdate' => true,
                'method' => function ($string) {
                    return str_replace(' ', '', title_case($string));
                }
            ],
        ];
    }
}
