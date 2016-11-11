<?php

namespace App\Http\Terranet\Administrator\Savers;

use Terranet\Administrator\Services\Saver;
use Terranet\Navigation\Providers\LinksProvider;
use Terranet\Navigation\Wrappers\Link;

class Menus extends Saver
{
    protected $ranking = [];

    protected function save()
    {
        $this->dropRemovedItems(
            $this->ranking = $this->request->get('ranking', [])
        );

        $this->saveNavigation();

        $this->syncRanking();

        parent::save();
    }


    /**
     * Create navigation item.
     *
     * @param $rank
     * @param $object
     * @param $provider
     * @return array
     */
    protected function createItem($rank, $object, $provider)
    {
        return $this->repository->items()
            ->create([
                'parent_id' => null,
                'rank' => $rank,
                'navigable' => array_merge($object->toArray(), [
                    'provider' => get_class($provider),
                ]),
            ]);
    }

    /**
     * Update new item ranking value.
     *
     * @param $provider
     * @param $originKey
     * @param $menuItem
     * @return array
     */
    protected function setRankingValue($provider, $originKey, $menuItem)
    {
        $searchable = get_class($provider) . '::' . $originKey;
        $position = array_search($searchable, $this->ranking);

        if (false !== $position) {
            $this->ranking[$position] = $menuItem->id;
        }
    }

    /**
     * Drop old items from navigation.
     *
     * @param $collection
     */
    protected function dropRemovedItems($collection)
    {
        $collection = array_filter($collection, function ($value) {
            return is_numeric($value);
        });

        $this->repository->items()
            ->whereNotIn('id', $collection)
            ->delete();
    }

    private function getProvider($provider)
    {
        return 'Links' == $provider
            ? app()->make(LinksProvider::class)
            : app()->make($provider);
    }

    protected function syncRanking()
    {
        foreach ($this->ranking as $position => $key) {
            $this->repository->items()->whereId($key)->update(['rank' => $position]);
        }
    }

    protected function saveNavigation()
    {
        $rank = 1;

        foreach ($providers = $this->request->get('navigable', []) as $provider => $links) {
            $provider = $this->getProvider($provider);

            foreach ($links as $key => $value) {
                if (LinksProvider::class == get_class($provider)) {
                    $originKey = $key;
                    $menuItem = $this->createItem($rank++, new Link($key, $value), $provider);
                } else {
                    $originKey = $value;
                    $menuItem = $this->createItem($rank++, $provider->find($value), $provider);
                }

                $this->setRankingValue($provider, $originKey, $menuItem);
            }
        }
    }
}