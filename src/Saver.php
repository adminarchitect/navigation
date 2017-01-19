<?php

namespace Terranet\Navigation;

use App\Http\Terranet\Administrator\Navigation\Providers\LinksProvider;
use Illuminate\Support\Collection;
use Terranet\Administrator\Services\Saver as AdministratorService;
use Terranet\Navigation\Wrappers\Link;

class Saver extends AdministratorService
{
    protected $ranking = [];

    protected function save()
    {
        $this->dropRemovedItems(
            $this->ranking = $this->parseRanking()
        );

        $this->saveNavigation();

        $this->syncRanking();

        parent::save();
    }


    /**
     * Create navigation item.
     *
     * @param $object
     * @param $provider
     * @param $position
     * @return array
     */
    protected function createItem($object, $provider, $position)
    {
        return $this->repository
            ->items()
            ->create([
                'rank' => $position,
                'navigable' => array_merge($object->toArray(), [
                    'provider' => get_class($provider),
                ]),
            ]);
    }

    /**
     * Drop old items from navigation.
     *
     * @param $collection
     */
    protected function dropRemovedItems(Collection $collection)
    {
        $this->repository->items()
                         ->whereNotIn('id', $collection->flatten())
                         ->delete();
    }

    private function getProvider($provider)
    {
        return 'Links' == $provider
            ? app()->make(LinksProvider::class)
            : app()->make($provider);
    }

    protected function syncRanking($source = null, $parent = null)
    {
        $positions = $source ?: $this->ranking;

        foreach ($positions as $position => $group) {
            $this->repository
                ->items()
                ->whereId($group['id'])
                ->update([
                    'parent_id' => $parent,
                    'rank' => $position,
                ]);

            if ($children = array_get($group, 'children')) {
                $this->syncRanking($children, $group['id']);
            }
        }
    }

    protected function saveNavigation()
    {
        foreach ($providers = $this->request->get('navigable', []) as $provider => $links) {
            $provider = $this->getProvider($provider);

            $rank = $this->repository->items->max('rank') + 1;
            foreach ($links as $key => $value) {
                if (LinksProvider::class == get_class($provider)) {
                    $this->createItem(new Link($key, $value), $provider, $rank++);
                } else {
                    $this->createItem($provider->find($value), $provider, $rank++);
                }
            }
        }
    }

    protected function parseRanking()
    {
        return collect(json_decode($this->request->get('ranking', '{}'), true));
    }
}