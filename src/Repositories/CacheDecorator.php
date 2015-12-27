<?php

namespace TypiCMS\Modules\Menus\Repositories;

use TypiCMS\Modules\Core\Repositories\CacheAbstractDecorator;
use TypiCMS\Modules\Core\Services\Cache\CacheInterface;

class CacheDecorator extends CacheAbstractDecorator implements MenuInterface
{
    public function __construct(MenuInterface $repo, CacheInterface $cache)
    {
        $this->repo = $repo;
        $this->cache = $cache;
    }

    /**
     * Get all models.
     *
     * @param array $with Eager load related models
     * @param bool  $all  Show published or all
     *
     * @return \Illuminate\Database\Eloquent\Collection|\TypiCMS\NestableCollection
     */
    public function all(array $with = [], $all = false)
    {
        $cacheKey = md5(config('app.locale').'all'.$all);

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        // Item not cached, retrieve it
        $models = $this->repo->all($with, $all);

        // Store in cache for next request
        $this->cache->put($cacheKey, $models);

        return $models;
    }

    /**
     * Render a menu.
     *
     * @param string $name menu name
     *
     * @return string html code of a menu
     */
    public function render($name)
    {
        return $this->repo->render($name);
    }

    /**
     * Get a menu.
     *
     * @param string $name menu name
     *
     * @return \TypiCMS\Modules\Menus\Models\Menu|null
     */
    public function getMenu($name)
    {
        return $this->repo->getMenu($name);
    }
}
