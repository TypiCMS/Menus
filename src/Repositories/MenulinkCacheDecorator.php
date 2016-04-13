<?php

namespace TypiCMS\Modules\Menus\Repositories;

use Illuminate\Database\Eloquent\Collection;
use TypiCMS\Modules\Core\Repositories\CacheAbstractDecorator;
use TypiCMS\Modules\Core\Services\Cache\CacheInterface;

class MenulinkCacheDecorator extends CacheAbstractDecorator implements MenulinkInterface
{
    public function __construct(MenulinkInterface $repo, CacheInterface $cache)
    {
        $this->repo = $repo;
        $this->cache = $cache;
    }
}
