<?php

namespace Terranet\Navigation\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model implements \IteratorAggregate
{
    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * Navigable items relationship.
     *
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('rank', 'ASC');
    }

    /**
     * Get collection iterator.
     *
     * @return Collection
     */
    public function getIterator()
    {
        return $this->items->map(function (MenuItem $item) {
            return $item->refresh();
        });
    }
}
