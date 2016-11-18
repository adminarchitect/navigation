<?php

namespace Terranet\Navigation\Models;

use Illuminate\Database\Eloquent\Model;
use Terranet\Navigation\URLContainer;

class MenuItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'menu_id', 'parent_id', 'rank', 'navigable',
    ];

    protected $casts = [
        'navigable' => 'json',
    ];

    /**
     * Restore item from database and prepare for editing.
     *
     * @return array|null
     */
    public function refresh()
    {
        return app()
            ->make($this->navigable['provider'])
            ->refresh($this);
    }

    /**
     * Convert stored item to URLContainer.
     *
     * @return null|URLContainer
     */
    public function assemble()
    {
        return app()
            ->make($this->navigable['provider'])
            ->assemble($this->navigable);
    }
}
